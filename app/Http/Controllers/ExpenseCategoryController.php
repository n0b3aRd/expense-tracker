<?php

namespace App\Http\Controllers;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('expense_category.index');
    }
}
