<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'paid_at' => 'date',
    ];

    public function category()
    {
        return $this->hasOne(ExpenseCategory::class, 'id', 'category_id');
    }

    public static function updateMonthTotal($month_id )
    {
        $total = Expense::where('month_id', $month_id)->where('status', 'Paid')->sum('amount');
        Month::where('id', $month_id)->update(['total_expense' => $total]);
    }
}
