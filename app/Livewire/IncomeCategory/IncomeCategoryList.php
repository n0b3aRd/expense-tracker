<?php

namespace App\Livewire\IncomeCategory;

use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\RegularIncome;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class IncomeCategoryList extends Component
{
    public $table_entries_count = 10;
    public $table_single_search = '';
    public $target = '';
    public $name = '';

    public function render()
    {
        $categories = IncomeCategory::select('id', 'name')
            ->when($this->table_single_search != '', function ($q) {
                return $q->where('name', 'like', '%'.$this->table_single_search.'%');
            })
            ->latest()
            ->paginate($this->table_entries_count);
        return view('livewire.income-category.income-category-list', compact('categories'));
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('income_categories', 'name')->whereNull('deleted_at')->ignore($this->target)]
        ];
    }

    public function create()
    {
        $this->resetValidation();
        $this->name = '';
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
            IncomeCategory::create([
                'name' => $this->name
            ]);
            DB::commit();
            Toaster::success('Income category created successfully');
            $this->dispatch('income-category-saved');
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
        $income_category = IncomeCategory::select('name')->where('id', $id)->first();
        $this->name = $income_category->name;
    }

    public function update()
    {
        $this->validate();

        try {
            IncomeCategory::where('id', $this->target)->update([
                'name' => $this->name
            ]);
            Toaster::success('Income category updated successfully');
            $this->dispatch('income-category-updated');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function delete()
    {
        $incomes = Income::select('id')->where('category_id', $this->target)->first();
        $regular_income = RegularIncome::select('id')->where('income_category_id', $this->target)->first();
        if ($incomes || $regular_income) {
            Toaster::error('Can\'t delete. Category already in use');
            return;
        }
        try {
            IncomeCategory::where('id', $this->target)->delete();
            Toaster::success('Income category deleted successfully');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
