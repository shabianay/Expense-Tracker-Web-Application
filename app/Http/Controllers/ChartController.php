<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function index(Request $request)
    {
        $userId = $this->getUserId();
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $type = $request->input('type', 'expense');
        $startOfMonth = Carbon::parse($month)->startOfMonth();
        $endOfMonth = Carbon::parse($month)->endOfMonth();

        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->where('type', 'expense')->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $balance = $totalIncome - $totalExpense;
        $dailySpent = $totalExpense / max(1, $startOfMonth->daysInMonth);

        $categoryTotal = Transaction::where('user_id', $userId)->where('type', $type)->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $categoryBreakdown = Transaction::where('transactions.user_id', $userId)
            ->where('transactions.type', $type)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name', 'categories.color', 'categories.icon', DB::raw('SUM(transactions.amount) as amount'))
            ->groupBy('categories.id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderByDesc('amount')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'color' => $row->color ?? '#4D96FF',
                'icon' => $row->icon ?? 'circle',
                'amount' => (float) $row->amount,
                'percentage' => $categoryTotal > 0 ? round(($row->amount / $categoryTotal) * 100, 1) : 0,
            ])
            ->all();

        $trendStartDate = Carbon::parse($month)->subMonths(5)->startOfMonth();
        $trendEndDate = Carbon::parse($month)->endOfMonth();
        $monthlyTotals = Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$trendStartDate, $trendEndDate])
            ->selectRaw("strftime('%Y-%m', transaction_date) as month")
            ->selectRaw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income")
            ->selectRaw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $trendMonth = Carbon::parse($month)->subMonths($i);
            $monthKey = $trendMonth->format('Y-m');
            $income = (float) ($monthlyTotals[$monthKey]->income ?? 0);
            $expense = (float) ($monthlyTotals[$monthKey]->expense ?? 0);

            $trendData[] = ['month' => $trendMonth->format('M Y'), 'income' => $income, 'expense' => $expense, 'balance' => $income - $expense];
        }

        $summaryTable = $trendData;

        return view('app.charts.index', compact('month', 'totalIncome', 'totalExpense', 'balance', 'dailySpent', 'categoryBreakdown', 'trendData', 'summaryTable', 'type'));
    }
}
