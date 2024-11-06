<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Transaction extends Model
{
    use HasFactory, HasApiTokens;

    public const TRANSACTION_TYPE_INCOME = 1;
    public const TRANSACTION_TYPE_EXPENSE = 2;

    public const EXPENSE_TYPE_BILLS = 1;
    public const EXPENSE_TYPE_FOODS = 2;
    public const EXPENSE_TYPE_TRANSPORTATION = 3;
    public const EXPENSE_TYPE_SHOPPING = 4;
    public const EXPENSE_TYPE_VACATION = 5;
    public const EXPENSE_TYPE_ENTERTAINMENT = 6;

    public const EXPENSE_TYPE_BILLS_LABEL = 'BILLS';
    public const EXPENSE_TYPE_FOODS_LABEL = 'FOODS AND DRINKS';
    public const EXPENSE_TYPE_TRANSPORTATION_LABEL = 'TRANSPORTATION';
    public const EXPENSE_TYPE_SHOPPING_LABEL = 'SHOPPING';
    public const EXPENSE_TYPE_VACATION_LABEL = 'VACATION';
    public const EXPENSE_TYPE_ENTERTAINMENT_LABEL = 'ENTERTAINMENT';

    protected $guarded = [];

    public static function sumTransaction($userId, $transactionType = '')
    {
        $transaction = Transaction::where('user_id', $userId);

        if ($transactionType) {
            $transaction->where('transaction_type', $transactionType);
        }

        return $transaction->sum('amount');
    }

    public static function getExpenseTypeName($expenseType)
    {
        switch ($expenseType) {
            case Transaction::EXPENSE_TYPE_BILLS:
                return Transaction::EXPENSE_TYPE_BILLS_LABEL;
            case Transaction::EXPENSE_TYPE_FOODS:
                return Transaction::EXPENSE_TYPE_FOODS_LABEL;
            case Transaction::EXPENSE_TYPE_TRANSPORTATION:
                return Transaction::EXPENSE_TYPE_TRANSPORTATION_LABEL;
            case Transaction::EXPENSE_TYPE_SHOPPING:
                return Transaction::EXPENSE_TYPE_SHOPPING_LABEL;
            case Transaction::EXPENSE_TYPE_VACATION:
                return Transaction::EXPENSE_TYPE_VACATION_LABEL;
            case Transaction::EXPENSE_TYPE_ENTERTAINMENT:
                return Transaction::EXPENSE_TYPE_ENTERTAINMENT_LABEL;
        }
    }
}
