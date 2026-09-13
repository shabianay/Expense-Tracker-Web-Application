<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $userId = $this->getUserId();
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();
        $transactions = Transaction::with('category')->where('user_id', $userId)->whereBetween('transaction_date', [$start, $end])->orderByDesc('transaction_date')->get();

        return response()->streamDownload(function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Type', 'Category', 'Note', 'Amount']);
            foreach ($transactions as $transaction) {
                fputcsv($file, [$transaction->transaction_date->format('Y-m-d H:i:s'), ucfirst($transaction->type), $transaction->category?->name ?? 'Unknown', $transaction->note ?? '', $transaction->amount]);
            }
            fclose($file);
        }, "transactions_{$month}.csv", ['Content-Type' => 'text/csv']);
    }
}
