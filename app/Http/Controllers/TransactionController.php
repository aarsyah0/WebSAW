<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()
            ->transactions()
            ->with('details.product')
            ->latest()
            ->paginate(10);

        return view('user.transactions', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        $transaction->load('details.product');
        return view('user.transaction-detail', compact('transaction'));
    }
}
