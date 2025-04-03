@extends('layouts.app',['guest' => true])
@section('title', 'Forgot Password')
@section('content')
<section class="auth-page bg-primary-25">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-8">
                <div class="auth-page-form">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset('images/auth-logo-2.png')}}" alt="auth-logo" class="mb-31 img-fluid auth-logo-img" width="183px" height="60px">
                        @if (session('success'))
                            @include('components.emailSuccess',['message' =>session('success'),'title' => session('title'),'status' => 'success'])
                        @endif

                        @if (session('error'))
                            @include('components.emailSuccess',['message' =>session('error'),'title' => session('error'),'status' => 'error'])
                        @endif
                        
                        <div class="auth-page-card bg-white rounded-lg">
                            <h2 class="text-gray-900 text-xl font-semibold mb-2">Forgot Password</h2>
                            <h3 class="text-gray-500 text-md font-regular mb-32">Enter the email address associated with your account to receive reset link</h3>
                            <form class="theme-form needs-validation" method="POST" action="{{ route('send.email') }}" novalidate>
                                @csrf
                                <div class="mb-32">
                                    <label for="email" class="text-gray-50 text-sm font-medium mb-6">{{ __('Email') }}<span class="required">*</span></label>

                                    <div class="">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus required>
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @else
                                            <div class="invalid-feedback">The email field is required.</div>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary text-md font-semibold d-block w-100 text-center mb-3">
                                    {{ __('Send') }}
                                </button>
                                <div class="text-center">
                                    @if (Route::has('password.request'))
                                        <a class="theme-link" href="{{ route('login') }}">
                                            Back to login
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
