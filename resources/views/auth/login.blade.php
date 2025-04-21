@extends('layouts.master-without-nav')

@section('title') @lang('translation.login') @endsection

@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<style>
    /* Background Styling */
    body {
        font-family: 'Cairo', sans-serif;
        direction: rtl; /* Right to Left */
        text-align: right;
        background: linear-gradient(to right, #283E51, #0A2342); /* Dark Blue Gradient */
        height: 100vh;
        margin: 0;
        display: flex;
        justify-content: center; /* Horizontally center the content */
        align-items: center; /* Vertically center the content */
    }

    /* Semi-transparent Login Box */
    .auth-container {
        max-width: 500px;  /* Fixed width for the form */
        width: 100%;
        padding: 30px;
        background: rgba(255, 255, 255, 0.95); /* Light Transparent Background */
        border-radius: 10px;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-in-out;
    }

    .auth-container:hover {
        transform: scale(1.02); /* Slight Hover Effect */
    }

    /* Logo Styling */
    .auth-logo img {
        max-height: 60px;
        display: block;
        margin: 0 auto 15px;
    }

    /* Form Inputs */
    .form-control {
        text-align: right;
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 12px;
        font-size: 16px;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #0A2342;
        box-shadow: 0px 0px 5px rgba(10, 35, 66, 0.3);
    }

    /* Primary Button */
    .btn-primary {
        width: 100%;
        background: #0A2342; /* Dark Blue */
        border: none;
        padding: 12px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background: #1A4D8F; /* Lighter Blue on Hover */
    }

    /* Footer Text */
    .auth-footer {
        font-size: 14px;
        color: #ccc;
    }
</style>
@endsection

@section('body')
<body>
@endsection

@section('content')
<div class="auth-container">
    <div class="text-center mb-4">
        <a href="index" class="auth-logo">
            <img src="{{ URL::asset('assets/images/logo.jpeg') }}" alt="Logo" class="img-fluid">
        </a>
        <h5 class="mt-3" style="color: #0A2342; font-weight: bold;">@lang('translation.welcome_back')</h5>
        <p class="text-muted">@lang('translation.sign_in_to_continue')</p>
    </div>

    <form method="POST" action="/login">
        @csrf
        <div class="mb-3">
            <label for="login" class="form-label">@lang('translation.phone_or_email')</label>
            <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" id="login" placeholder="@lang('translation.enter_phone_or_email')" required>
            @error('login')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">@lang('translation.password')</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" placeholder="@lang('translation.enter_password')" required>
            @error('password')
                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="d-grid mt-3">
            <button type="submit" class="btn btn-primary">@lang('translation.login')</button>
        </div>
    </form>

    <div class="mt-4 text-center auth-footer">
        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> LawyersClub</p>
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection
