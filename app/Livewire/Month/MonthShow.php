<?php

namespace App\Livewire\Month;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\Month;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class MonthShow extends Component
{
    public $month_id;
    public $expense_category_id;
    public $expense_name;
    public $expense_amount;
    public $paid_at;
    public $expense_status;
    public $expense_id;
    public $target;
    public $target_type;
    public $income_name;
    public $income_category_id;
    public $income_amount;
    public $collected_at;
    public $income_status;
    public $income_id;
    public $expense_summary;
    public $expense_chart_data;

    public function render()
    {
        $month = Month::select('id', 'name', 'month', 'pre_balance', 'total_income', 'total_expense')
            ->where('id', $this->month_id)
            ->first();
        $expenses = Expense::select('id','category_id', 'name', 'amount', 'paid_at', 'status')
            ->with('category:id,name')
            ->where('month_id', $this->month_id)
            ->get();
        $incomes = Income::select('id','category_id', 'name', 'amount', 'collected_at', 'status')
            ->with('category:id,name')
            ->where('month_id', $this->month_id)
            ->get();
        $expenses_categories = ExpenseCategory::select('id', 'name')->orderBy('name')->get();
        $income_categories = IncomeCategory::select('id', 'name')->orderBy('name')->get();
        return view('livewire.month.month-show', compact('month', 'expenses', 'incomes', 'expenses_categories', 'income_categories'));
    }

    public function mount()
    {
        $expenses_category_vice = Expense::selectRaw('category_id, sum(amount) as amount')->where('month_id', $this->month_id)
            ->where('status', 'Paid')
            ->groupBy('category_id')
            ->get()
            ->pluck('amount', 'category_id');
        $expense_categories = ExpenseCategory::select('id', 'name', 'budget_allocation')->get();
        $this->expense_summary = collect();
        $this->expense_chart_data = collect();
        foreach ($expense_categories as $expense_category) {

            if (isset($expenses_category_vice[$expense_category->id])) {
                $current_amount = $expenses_category_vice[$expense_category->id];
                if ($expense_category->budget_allocation == 0) {
                    $remaining = 0;
                } else {
                    $remaining = round((($expense_category->budget_allocation - $current_amount)/$expense_category->budget_allocation)*100, 1);
                }
            } else {
                $current_amount = 0;
                $remaining = 100;
            }
            $this->expense_summary->push([
                'id' => $expense_category->id,
                'name' => $expense_category->name,
                'budget_allocation' => $expense_category->budget_allocation,
                'current_amount' => $current_amount,
                'remaining' => $remaining,
            ]);
            $this->expense_chart_data->push([
                'x' => $expense_category->name,
                'y' => $current_amount,
                'goals' => [[
                    'name' => 'Allocation',
                    'value' => $expense_category->budget_allocation,
                    'strokeHeight' => 5,
                    'strokeColor' => '#2fb344',
                ]]
            ]);
        }
    }

    public function clearErrors()
    {
        $this->resetValidation();
    }

    public function saveExpense()
    {
        $this->validate([
            'expense_name' => 'required|max:150',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_amount' => 'required|min:0|numeric',
            'paid_at' => 'required|date',
            'expense_status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'month_id' => $this->month_id,
                'name' => $this->expense_name,
                'category_id' => $this->expense_category_id,
                'amount' => $this->expense_amount,
                'paid_at' => $this->paid_at,
                'status' => $this->expense_status ? 'Paid' : 'Pending',
            ];

            if ($this->expense_id != '') {
                Expense::where('id',$this->expense_id)->update($data);
            } else {
                Expense::create($data);
            }

            Expense::updateMonthTotal($data['month_id']);

            DB::commit();
            Toaster::success('Expense saved successfully');
            $this->dispatch('expense-saved');
        } catch (Exception $e) {
            DB::rollBack();
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function createExpense()
    {
        $this->resetValidation();
        $this->expense_name = '';
        $this->expense_category_id = '';
        $this->expense_amount = 0;
        $this->paid_at = date('Y-m-d');
        $this->expense_status = false;
        $this->expense_id = '';
    }

    public function updateExpense($expense_id)
    {
        $this->resetValidation();
        $this->expense_id = $expense_id;
        $expense = Expense::select('id', 'name', 'category_id', 'amount', 'status', 'paid_at')
            ->where('id', $expense_id)
            ->first();
        $this->expense_name = $expense->name;
        $this->expense_category_id = $expense->category_id;
        $this->expense_amount = $expense->amount;
        $this->paid_at = $expense->paid_at ? $expense->paid_at->format('Y-m-d') : date('Y-m-d');
        $this->expense_status = $expense->status == 'Paid' ? true : false;
    }

    public function setTarget($id, $type)
    {
        $this->target = $id;
        $this->target_type = $type;
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            if ($this->target_type == 'expense') {
                $expense = Expense::where('id', $this->target)->find();
                $month_id = $expense->month_id;
                $expense->delete();

                Expense::updateMonthTotal($month_id);
                Toaster::success('Expense deleted successfully');
            } elseif ($this->target_type == 'income') {
                $income = Income::where('id', $this->target)->find();
                $month_id = $income->month_id;
                $income->delete();

                Income::updateMonthTotal($month_id);
                Toaster::success('Income deleted successfully');
            }
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function updateIncome($income_id)
    {
        $this->resetValidation();
        $this->income_id = $income_id;
        $income = Income::select('id', 'name', 'category_id', 'amount', 'status', 'collected_at')
            ->where('id', $income_id)
            ->first();
        $this->income_name = $income->name;
        $this->income_category_id = $income->category_id;
        $this->income_amount = $income->amount;
        $this->collected_at = $income->collected_at ? $income->collected_at->format('Y-m-d') : date('Y-m-d');
        $this->income_status = $income->status == 'Collected' ? true : false;
    }

    public function createIncome()
    {
        $this->resetValidation();
        $this->income_name = '';
        $this->income_category_id = '';
        $this->income_amount = 0;
        $this->collected_at = date('Y-m-d');
        $this->income_status = false;
        $this->income_id = '';
    }

    public function saveIncome()
    {
        $this->validate([
            'income_name' => 'required|max:150',
            'income_category_id' => 'required|exists:income_categories,id',
            'income_amount' => 'required|min:0|numeric',
            'collected_at' => 'required|date',
            'income_status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'month_id' => $this->month_id,
                'name' => $this->income_name,
                'category_id' => $this->income_category_id,
                'amount' => $this->income_amount,
                'collected_at' => $this->collected_at,
                'status' => $this->income_status ? 'Collected' : 'Pending',
            ];

            if ($this->income_id != '') {
                Income::where('id',$this->income_id)->update($data);
            } else {
                Income::create($data);
            }

            Income::updateMonthTotal($data['month_id']);

            DB::commit();
            Toaster::success('Income saved successfully');
            $this->dispatch('income-saved');
        } catch (Exception $e) {
            DB::rollBack();
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
