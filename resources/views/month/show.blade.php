@extends('layouts.app')

@section('custom_styles')

@endsection

@section('content')
    <div class="container-xl">
        <livewire:month.month-show :month_id="$month"/>
    </div>
@endsection
@section('custom_scripts')

@endsection