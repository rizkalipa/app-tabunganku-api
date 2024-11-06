<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UserBalanceDeposit extends Model
{
    use HasFactory, HasApiTokens;

    protected $primaryKey = 'user_id';

    protected $guarded = [];

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
