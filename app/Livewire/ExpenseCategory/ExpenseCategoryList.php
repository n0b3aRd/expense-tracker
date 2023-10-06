<?php

namespace App\Livewire\ExpenseCategory;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\RegularExpense;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class ExpenseCategoryList extends Component
{
    public $table_entries_count = 10;
    public $table_single_search = '';
    public $target = '';
    public $name = '';
    public $budget_allocation = 0;

    public function render()
    {
        $categories = ExpenseCategory::select('id', 'name', 'budget_allocation')
            ->when($this->table_single_search != '', function ($q) {
                return $q->where('name', 'like', '%'.$this->table_single_search.'%');
            })
            ->latest()
            ->paginate($this->table_entries_count);
        return view('livewire.expense-category.expense-category-list', compact('categories'));
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('expense_categories', 'name')->whereNull('deleted_at')->ignore($this->target)],
            'budget_allocation' => 'nullable|numeric|min:0'
        ];
    }

    public function create()
    {
        $this->resetValidation();
        $this->name = '';
        $this->budget_allocation = 0;
        $this->target = '';
    }

    public function setTarget($id)
    {
        $this->target = $id;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            ExpenseCategory::create([
                'name' => $this->name,
                'budget_allocation' => $this->budget_allocation,
            ]);
            DB::commit();
            Toaster::success('Expense category created successfully');
            $this->dispatch('expense-category-saved');
        } catch (Exception $e) {
            DB::rollBack();
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function edit($id)
    {
        $this->resetValidation();
        $this->target = $id;
        $expense_category = ExpenseCategory::select('name', 'budget_allocation')->where('id', $id)->first();
        $this->name = $expense_category->name;
        $this->budget_allocation = $expense_category->budget_allocation;
    }

    public function update()
    {
        $this->validate();

        try {
            ExpenseCategory::where('id', $this->target)->update([
                'name' => $this->name,
                'budget_allocation' => $this->budget_allocation,
            ]);
            Toaster::success('Expense category updated successfully');
            $this->dispatch('expense-category-updated');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function delete()
    {
        $expense = Expense::select('id')->where('category_id', $this->target)->first();
        $regular_expense = RegularExpense::select('id')->where('expense_category_id', $this->target)->first();
        if ($expense || $regular_expense) {
            Toaster::error('Can\'t delete. Category already in use');
            return;
        }
        try {
            ExpenseCategory::where('id', $this->target)->delete();
            Toaster::success('Expense category deleted successfully');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
