<?php

namespace App\Http\Controllers;

use App\Models\Month;
use Illuminate\Support\Facades\Redirect;

class MonthController extends Controller
{
    public function index()
    {
        return view('month.index');
    }

    public function show($month)
    {
        $current_month = Month::find($month);
        if (!$current_month) {
            return Redirect::back()
                ->error('Current month not found');
        }
        return view('month.show', compact('month'));
    }
}
