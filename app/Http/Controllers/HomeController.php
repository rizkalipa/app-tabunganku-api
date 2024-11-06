<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use App\Models\Transaction;
use App\Models\UserBalanceDeposit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getBalance(Request $request) {
        $balance = UserBalanceDeposit::where('user_id', $request->user()->id)
            ->with('user')
            ->get();

        return $this->sendResponse($balance, 'get deposit balance');
    }

    public function getBalancePercentage(Request $request) {
        $userId = $request->user()->id;
        $balance = UserBalanceDeposit::where('user_id', $userId)->sum('amount');
        $income = Transaction::sumTransaction($userId, Transaction::TRANSACTION_TYPE_INCOME);
        $expense = Transaction::sumTransaction($userId, Transaction::TRANSACTION_TYPE_EXPENSE);
        $incomeGoals = Transaction::select('amount')
            ->where([
                'user_id' => $userId,
                'transaction_type' => Transaction::TRANSACTION_TYPE_INCOME
            ])
            ->whereNotNull('saving_goal_id')
            ->sum('amount');

        $result = [
            'income' => ($balance / $income) * 100,
            'expense' => ($expense / $income) * 100,
            'left' => $incomeGoals ? ($balance / ($income - $incomeGoals)) * 100 : 0,
        ];

        return $this->sendResponse($result, 'Get balance percentage');
    }

    public function getRecentTransaction(Request $request) {
        $transactions = Transaction::limit(3)->get();

        return $this->sendResponse($transactions, 'recent transactions');
    }

    public function getRecentGoals(Request $request) {
        $userId = $request->user()->id;
        $goals = SavingGoal::where('user_id', $userId)->limit(5)->get();

        return $this->sendResponse($goals, 'get recent goals');
    }
}
