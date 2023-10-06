<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Month extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'month' => 'date'
    ];

    public function setRegularTransactions()
    {
        //get previous balance
        $previous_month = Carbon::parse($this->month)->subMonth();
        $previous_month = Month::where('month', $previous_month)->first();
        $pre_balance = 0;
        if ($previous_month) {
            $pre_balance = ($previous_month->pre_balance + $previous_month->total_income) - $previous_month->total_expense;
        }

        //get regular incomes
        $incomes = [];
        $regular_incomes = RegularIncome::select('income_category_id', 'name', 'regular_amount')->get();
        foreach ($regular_incomes as $regular_income) {
            $incomes[] = [
                'month_id' => $this->id,
                'category_id' => $regular_income->income_category_id,
                'name' => $regular_income->name,
                'amount' => $regular_income->regular_amount,
                'status' => 'Pending',
                'collected_at' => null,
            ];
        }
        Income::insert($incomes);

        //get regular expenses
        $expenses = [];
        $regular_expenses = RegularExpense::select('expense_category_id', 'name', 'regular_amount')->get();
        foreach ($regular_expenses as $regular_expense) {
            $expenses[] = [
                'month_id' => $this->id,
                'category_id' => $regular_expense->expense_category_id,
                'name' => $regular_expense->name,
                'amount' => $regular_expense->regular_amount,
                'status' => 'Pending',
                'paid_at' => null,
            ];
        }
        Expense::insert($expenses);
    }
}
