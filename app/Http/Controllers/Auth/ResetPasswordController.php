<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ResetPasswordController extends Controller
{
    public function create(Request $request)
    {
        return view('auth.verify-reset-password', [
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $resetData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        $isCodeExpired = !$resetData || now()->diffInMinutes(Carbon::parse($resetData->created_at)) > 15;

        if ($isCodeExpired || !Hash::check($request->code, $resetData->token)) {
            throw ValidationException::withMessages([
                'code' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.',
            ]);
        }

        DB::table('users')
            ->where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
                'updated_at' => now(),
            ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login kembali.');
    }
}
