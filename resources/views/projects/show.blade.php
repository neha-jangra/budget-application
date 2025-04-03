@extends('layouts.app')
@section('title', 'Core Support')
@section('content')
<style>
    .inactive-btn{
        opacity: 0.5;
        cursor: not-allowed;
    }
    </style>
    @livewire('project.show')
    
@endsection