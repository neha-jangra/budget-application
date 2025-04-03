@extends('layouts.app',['guest' => true])
@section('title', 'Reset Password')
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
                            @include('components.emailSuccess',['message' =>session('success'),'title' => session('title'),'status' => 'error'])
                        @endif
                    <div class="auth-page-card bg-white rounded-lg">
                        <h2 class="text-gray-900 text-xl font-semibold mb-2">{{ __('Create new password') }}</h2>
                        <h3 class="text-gray-500 text-md font-regular mb-32">{{ __('Fill all the form bellow to create your new password.') }}</h3>
                        <form method="POST" class="theme-form" action="{{ route('password.change') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="password" class="text-gray-50 text-sm font-medium p-0 mb-6">{{ __('Password') }}<span class="required">*</span></label>
                                <div>
                                    <div class="input-addon">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" value="{{old('password')}}">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        <div class="add-on" onclick="togglePasswordVisibility()">
                                            <img id="eyeIcon" src="images/icons/eye-slash.svg" alt="eye-slash">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="mb-32">
                                <label for="confirm_password" class="text-gray-50 text-sm font-medium p-0 mb-6">{{ __('Confirm Password') }}<span class="required">*</span></label>
                                <div>
                                    <div class="input-addon">
                                        <input id="confirm_password" type="password" class="form-control @error('confirm_password') is-invalid @enderror" name="confirm_password"  autocomplete="current-password" value="{{old('confirm_password')}}">
                                        @error('confirm_password')
                                            <span class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                        <input  type="hidden" class="form-control" name="email" value="{{ request()->get('email') }}">
                                        <div class="add-on" onclick="confirmTogglePasswordVisibility()">
                                            <img id="confirm_pass_eyeIcon" src="images/icons/eye-slash.svg" alt="eye-slash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-md font-semibold d-block w-100 text-center">
                                {{ __('Submit') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
