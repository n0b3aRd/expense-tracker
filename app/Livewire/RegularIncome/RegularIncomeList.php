<?php

namespace App\Livewire\RegularIncome;

use App\Models\IncomeCategory;
use App\Models\RegularIncome;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class RegularIncomeList extends Component
{
    public $table_entries_count = 10;
    public $category_search = '';
    public $name_search = '';
    public $target = '';
    public $name = '';
    public $income_category_id = '';
    public $regular_amount = 0;

    public function render()
    {
        $categories = IncomeCategory::select('id', 'name')->orderBy('name')->get();
        $incomes = RegularIncome::select('id', 'income_category_id', 'name', 'regular_amount')
            ->with('incomeCategory:id,name')
            ->when($this->name_search != '', function ($q) {
                return $q->where('name', 'like', '%'.$this->name_search.'%');
            })
            ->when($this->category_search != '', function ($q) {
                return $q->where('income_category_id', $this->category_search);
            })
            ->latest()
            ->paginate($this->table_entries_count);
        return view('livewire.regular-income.regular-income-list', compact('incomes', 'categories'));
    }

    public function rules()
    {
        return [
            'income_category_id' => ['required', 'exists:income_categories,id'],
            'name' => ['required', Rule::unique('regular_incomes', 'name')->whereNull('deleted_at')->ignore($this->target)],
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
            RegularIncome::create([
                'income_category_id' => $this->income_category_id,
                'name' => $this->name,
                'regular_amount' => $this->regular_amount ?? 0,
            ]);
            DB::commit();
            Toaster::success('Regular income created successfully');
            $this->dispatch('regular-income-saved');
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
        $regular_income = RegularIncome::select('name', 'income_category_id', 'regular_amount')->where('id', $this->target)->first();
        $this->name = $regular_income->name;
        $this->income_category_id = $regular_income->income_category_id;
        $this->regular_amount = $regular_income->regular_amount;
    }

    public function update()
    {
        $this->validate();

        try {
            RegularIncome::where('id', $this->target)->update([
                'income_category_id' => $this->income_category_id,
                'name' => $this->name,
                'regular_amount' => $this->regular_amount ?? 0,
            ]);
            Toaster::success('Regular income updated successfully');
            $this->dispatch('regular-income-updated');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }

    public function delete()
    {
        try {
            RegularIncome::where('id', $this->target)->delete();
            Toaster::success('Regular income deleted successfully');
        } catch (Exception $e) {
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
