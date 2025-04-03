@extends('layouts.app')
@section('title', 'Line Items')
@section('content')
    <div class="main-content" role="main">
        @livewire('lineitem.lineitem')
    </div>
@endsection