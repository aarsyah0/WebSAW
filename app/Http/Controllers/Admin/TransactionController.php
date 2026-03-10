<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user', 'details.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'details.product');

        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => ['required', 'in:pending,paid,processing,shipped,completed,cancelled']]);
        $transaction->update(['status' => $request->status]);
        return back()->with('success', 'Status transaksi diperbarui.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Transaction::with('user', 'details.product');
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        $transactions = $query->latest()->get();

        $filename = 'laporan-transaksi-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($transactions) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['Kode', 'Customer', 'Tanggal', 'Total', 'Status']);
            foreach ($transactions as $t) {
                fputcsv($out, [
                    $t->code,
                    $t->user->name ?? '-',
                    $t->created_at->format('d/m/Y H:i'),
                    $t->total,
                    $t->status,
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }
}
