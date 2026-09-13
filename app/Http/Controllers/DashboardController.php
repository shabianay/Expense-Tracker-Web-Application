<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $this->getUserId();
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $monthly = Transaction::where('user_id', $userId)->whereBetween('transaction_date', [$startOfMonth, $endOfMonth]);
        $totalIncome = (clone $monthly)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $monthly)->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->orderByDesc('transaction_date')
            ->get()
            ->groupBy(fn (Transaction $transaction) => $transaction->transaction_date->format('Y-m-d'))
            ->map(fn ($items, $date) => [
                'date' => $date,
                'dayExpense' => $items->where('type', 'expense')->sum('amount'),
                'dayIncome' => $items->where('type', 'income')->sum('amount'),
                'transactions' => $items,
            ])
            ->values();

        return view('app.dashboard', compact('month', 'totalIncome', 'totalExpense', 'balance', 'transactions'));
    }
}
