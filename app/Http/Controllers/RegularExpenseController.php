<?php

namespace App\Http\Controllers;

class RegularExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('regular_expense.index');
    }
}
