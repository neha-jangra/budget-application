@extends('layouts.app',['guest' => true])
@section('title', 'Login')
@section('content')
<section class="auth-page bg-primary-25">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="auth-page-form">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset('images/auth-logo-2.png')}}" alt="auth-logo" class="mb-31 img-fluid auth-logo-img" width="183px" height="60px">
                    @if (session('success'))
                        @include('components.emailSuccess',['message' =>session('success'),'title' => session('title'),'status' => 'success'])
                    @endif

                    @if (session('error'))
                        @include('components.emailSuccess',['message' =>session('error'),'title' => session('title'),'status' => 'error'])   
                    @endif
                    <div class="auth-page-card bg-white rounded-lg">
                        <h2 class="text-gray-900 text-xl font-semibold mb-32">{{ __('Log in to your account.') }}</h2>
                        <form method="POST" class="theme-form needs-validation" action="{{ route('login') }}" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="text-gray-50 text-sm font-medium p-0 mb-6">{{ __('Email') }}<span class="required">*</span></label>

                                <div class="">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                                    
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @else
                                        <div class="invalid-feedback">The email field is required.</div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-32">
                                <label for="password" class="text-gray-50 text-sm font-medium p-0 mb-6">{{ __('Password') }}<span class="required">*</span></label>
                                <div class="">
                                    <div class="input-addon">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password" required>
                                        
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                {{ $errors->first('password') }}
                                            </span>
                                        @else
                                            <div class="invalid-feedback">The password field is required</div>
                                        @endif
                                        <div class="add-on" onclick="togglePasswordVisibility()">
                                            <img id="eyeIcon" src="images/icons/eye-slash.svg" alt="eye-slash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-check remember-me mb-4 ps-0 d-flex align-items-center">
                                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                                <label for="remember" class="text-gray-50 text-sm font-medium ms-2">Remember me</label>
                            </div> -->
                            
                            <button type="submit" class="btn btn-primary text-md font-semibold d-block w-100 text-center mb-3">
                                {{ __('Login') }}
                            </button>

                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a class="theme-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                                
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
