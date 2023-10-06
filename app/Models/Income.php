<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'collected_at' => 'date',
    ];

    public function category()
    {
        return $this->hasOne(IncomeCategory::class, 'id', 'category_id');
    }

    public static function updateMonthTotal($month_id)
    {
        $total = Income::where('month_id', $month_id)->where('status', 'Collected')->sum('amount');
        Month::where('id', $month_id)->update(['total_income' => $total]);
    }
}
