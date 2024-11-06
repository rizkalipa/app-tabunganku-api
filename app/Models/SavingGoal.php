<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    use HasFactory;

    public const SAVING_GOAL_TYPE_TRAVEL = 1;
    public const SAVING_GOAL_TYPE_LAPTOP = 2;
    public const SAVING_GOAL_TYPE_GADGET = 3;
    public const SAVING_GOAL_TYPE_GOODS = 4;

    protected $guarded = [];
}
