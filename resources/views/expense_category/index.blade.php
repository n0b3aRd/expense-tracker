@extends('layouts.app')

@section('custom_styles')

@endsection

@section('content')
    <div class="container-xl">
        <livewire:expense-category.expense-category-list/>
    </div>
@endsection