@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="main-content" role="main">
        @livewire('user.edit', ['id' => request()->route('id')])
    </div>
@endsection