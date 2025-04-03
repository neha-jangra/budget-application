@extends('layouts.app')
@section('title', 'Donors')
@section('content')
    <div class="main-content" role="main">
        @livewire('donor.donor')
    </div>
    @include('components.donors.delete')
@endsection