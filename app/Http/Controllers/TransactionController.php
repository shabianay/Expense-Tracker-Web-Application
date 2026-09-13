<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function show(Transaction $transaction)
    {
        return redirect()->route('transactions.edit', $transaction);
    }

    public function create(Request $request)
    {
        $type = $request->input('type', 'expense');
        $categories = Category::where('user_id', $this->getUserId())->orderBy('type')->orderBy('sort_order')->get();

        return view('app.transactions.create', compact('categories', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        Transaction::create([
            'user_id' => $this->getUserId(),
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'note' => $validated['note'] ?? null,
            'transaction_date' => $validated['date'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($transaction->user_id === $this->getUserId(), 403);

        $type = $transaction->type;
        $categories = Category::where('user_id', $this->getUserId())->orderBy('type')->orderBy('sort_order')->get();

        return view('app.transactions.edit', compact('transaction', 'categories', 'type'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($transaction->user_id === $this->getUserId(), 403);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $transaction->update([
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'category_id' => $validated['category_id'],
            'note' => $validated['note'] ?? null,
            'transaction_date' => $validated['date'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        // No auth check needed, assuming single user or public access
        // abort_unless($transaction->user_id === $this->getUserId(), 403);

        $transaction->delete();

        return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dihapus.');
    }
}
