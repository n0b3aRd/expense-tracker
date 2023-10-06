<?php

namespace App\Livewire\RegularExpense;

use App\Models\ExpenseCategory;
use App\Models\RegularExpense;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class RegularExpenseList extends Component
{
    public $table_entries_count = 10;
    public $category_search = '';
    public $name_search = '';
    public $target = '';
    public $name = '';
    public $expense_category_id = '';
    public $regular_amount = 0;

    public function render()
    {
        $categories = ExpenseCategory::select('id', 'name')->orderBy('name')->get();
        $expenses = RegularExpense::select('id', 'expense_category_id', 'name', 'regular_amount')
            ->with('expenseCategory:id,name')
            ->when($this->name_search != '', function ($q) {
                return $q->where('name', 'like', '%'.$this->name_search.'%');
            })
            ->when($this->category_search != '', function ($q) {
                return $q->where('expense_category_id', $this->category_search);
            })
            ->latest()
            ->paginate($this->table_entries_count);
        return view('livewire.regular-expense.regular-expense-list', compact('expenses', 'categories'));
    }

    public function rules()
    {
        return [
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'name' => ['required', Rule::unique('regular_expenses', 'name')->whereNull('deleted_at')->ignore($this->target)],
            'regular_amount' => 'nullable|min:0|numeric'
        ];
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset();
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
            RegularExpense::create([
                'expense_category_id' => $this->expense_category_id,
                'name' => $this->name,
                'regular_amount' => $this->regular_amount ?? 0,
            ]);
            DB::commit();
            Toaster::success('Regular expense created successfully');
            $this->dispatch('regular-expense-saved');
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
        $regular_expense = RegularExpense::select('name', 'expense_category_id', 'regular_amount')->where('id', $this->target)->first();
        $this->name = $regular_expense->name;
        $this->expense_category_id = $regular_expense->expense_category_id;
        $this->regular_amount = $regular_expense->regular_amount;
    }

    public function update()
    {
        $this->validate();

        try {
            RegularExpense::where('id', $this->target)->update([
                'expense_category_id' => $this->expense_category_id,
                'name' => $this->name,
                'regular_amount' => $this->regular_amount  ?? 0,
            ]);
            Toaster::success('Regular expense updated successfully');
            $this->dispatch('regular-expense-updated');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function delete()
    {
        try {
            RegularExpense::where('id', $this->target)->delete();
            Toaster::success('Regular expense deleted successfully');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
