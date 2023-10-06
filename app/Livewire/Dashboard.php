<?php

namespace App\Livewire;

use App\Models\Month;
use Livewire\Component;

class Dashboard extends Component
{
    public $current_month_id;

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function mount()
    {
        $current_month = Month::select('id')->where('month', date('Y-m').'-01')->first();
        $this->current_month_id = $current_month ? $current_month->id : 0;
    }
}
