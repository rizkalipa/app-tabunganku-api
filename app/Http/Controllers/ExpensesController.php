<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class ExpensesController extends Controller
{
    public function getExpensesChart(Request $request) {
        $userId = $request->user()->id;
        $transactions = Transaction::select('expense_type', DB::raw('COUNT(*) AS total'))
            ->where([
                'user_id' => $userId,
                'transaction_type' => Transaction::TRANSACTION_TYPE_EXPENSE
            ])
            ->groupBy('expense_type')
            ->get();

        $transactions = $transactions->map(function ($item) {
           return [
               'expense_type' => ucfirst(strtolower(Transaction::getExpenseTypeName($item->expense_type))),
               'total' => $item->total
           ];
        });

        return $this->sendResponse($transactions, 'get expenses chart');
    }

    public function getExpensesByDate(Request $request) {
        $userId = $request->user()->id;
        $transactions = Transaction::select('id', 'expense_type', 'amount', 'notes', 'created_at', DB::raw('date(created_at) AS date_at'))
            ->where([
                'user_id' => $userId,
                'transaction_type' => Transaction::TRANSACTION_TYPE_EXPENSE
            ])
            ->get()
            ->groupBy('date_at');
        $formatData = [];

        foreach ($transactions as $key => $transaction) {
            $date = Carbon::parse($key)->format('l, jS F Y');
            $transaction->each(fn ($item) => $item->expense_type = ucwords(strtolower(Transaction::getExpenseTypeName($item->expense_type))));

            if (Carbon::now()->isSameDay(Carbon::parse($key))) {
                $date = 'Today';
            }

            $formatData[$date] = $transaction;
        }

        return $this->sendResponse($formatData, 'get expenses by date');
    }
}
