@extends('layouts.app',['guest' => true])
@section('title', 'Reset Code')
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
                        <h2 class="text-gray-900 text-xl font-semibold mb-2">{{ __('Reset number') }}</h2>
                        <h3 class="text-gray-500 text-md font-regular mb-32">{{ __('Enter the 4-digit verification code that was sent to your email to change your password.') }}</h3>
                        <form class="theme-form" method="POST" action="{{ route('verify.code')}}">
                            @csrf
                            <div class="mb-32">
                                <div class="d-flex gap-3 align-items-center">
                                    <input type="hidden" class="form-control reset-code-input" name="email" value="{{ request()->get('email') }}" required>
                                    <input type="text" class="form-control  reset-code-input" oninput="digitValidate('input1')" id="input1" onkeyup="tabChange(event, 1)"  maxlength=1  name="code[]" required autocomplete="off">
                                    <input type="text" class="form-control reset-code-input" oninput="digitValidate('input2')" id="input2"onkeyup='tabChange(event,2)'  maxlength=1  name="code[]" required autocomplete="off">
                                    <input type="text" class="form-control reset-code-input" oninput="digitValidate('input3')" id="input3" onkeyup='tabChange(event,3)'  maxlength=1  name="code[]" required autocomplete="off">
                                    <input type="text" class="form-control reset-code-input"oninput="digitValidate('input4')"  id="input4" onkeyup='tabChange(event,4)'  maxlength=1  name="code[]" required autocomplete="off">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-md font-semibold d-block w-100 text-center">
                                {{ __('Next') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
