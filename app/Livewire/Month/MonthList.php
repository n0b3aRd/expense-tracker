<?php

namespace App\Livewire\Month;

use App\Models\Month;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class MonthList extends Component
{
    public $current_year;
    public $current_month_id;
    public $previous_year;
    public $next_year;
    public $selected_year;
    public $input_month;
    public $input_year;
    public $month;

    public function render()
    {
        $months = Month::select('id', 'name', 'month', 'total_income', 'total_expense')
            ->whereYear('month', $this->selected_year)
            ->orderBy('month')
            ->get();
        return view('livewire.month.month-list', compact('months'));
    }

    public function mount()
    {
        $this->current_year = (integer)date('Y');
        $this->selected_year = $this->current_year;
        $this->previous_year = $this->current_year -1;
        $this->next_year = $this->current_year +1;
        $current_month = Month::select('id')->where('month', date('Y-m').'-01')->first();
        $this->current_month_id = $current_month ? $current_month->id : 0;
    }

    public function previousYear()
    {
        $this->selected_year--;
        $this->previous_year--;
        $this->next_year--;
    }

    public function nextYear()
    {
        $this->selected_year++;
        $this->previous_year++;
        $this->next_year++;
    }

    public function create()
    {
        $this->resetValidation();
        $this->input_month = '';
        $this->input_year = $this->current_year;
        $this->month = '';
    }

    public function rules()
    {
        return [
            'month' => 'required|date|unique:months,month'
        ];
    }

    public function save()
    {
        $this->month = $this->input_year.'-'.$this->input_month.'-01';
        $this->validateOnly('month');

        $previous_month = Carbon::parse($this->month)->subMonth();
        $previous_month = Month::where('month', $previous_month)->first();
        $pre_balance = 0;
        if ($previous_month) {
            $pre_balance = ($previous_month->pre_balance + $previous_month->total_income) - $previous_month->total_expense;
        }

        DB::beginTransaction();
        try {
            $month = Month::create([
                'name' => Carbon::parse($this->month)->format('F'),
                'month' => $this->month,
            ]);

            $month->setRegularTransactions();

            DB::commit();
            Toaster::success('Month created successfully');
            $this->dispatch('month-saved');
        } catch (Exception $e) {
            DB::rollBack();
            addErrorToLog($e);
            Toaster::error('Something went wrong!');
        }
    }
}
