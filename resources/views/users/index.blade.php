@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="main-content" role="main">
        @livewire('user.user')
    </div>
@endsection