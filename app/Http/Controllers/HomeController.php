<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');
        }
        $featuredProducts = Product::where('stock', '>', 0)->latest()->take(8)->get();
        return view('welcome', compact('featuredProducts'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        return view('user.dashboard', compact('totalTransactions', 'recentTransactions'));
    }
}
