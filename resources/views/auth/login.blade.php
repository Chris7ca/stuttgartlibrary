@extends('layouts.auth')

@section('title_page', 'Login')

@section('content_page')

    <div class="uk-width-1-2 uk-position-center">

        <a href="{{ route('home') }}">
            <img src="{{ asset('images/stuttgart-logo.png') }}" alt="Stuttgart Library Logo">
        </a>

        <h1>Login for more actions</h1>

        @error('email')
            <div class="uk-alert uk-alert-danger">
                <span class="uk-text-danger uk-text-bold">Invalid credentials</span>
            </div>
        @enderror

        <form class="uk-margin-medium" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="uk-margin">
                <input type="text" class="uk-input" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="Your email" autofocus required>
            </div>
            <div class="uk-margin">
                <input class="uk-input" name="password" type="password" placeholder="Your password" required>
            </div>
            <div class="uk-margin">
                <label>
                    <input class="uk-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
            </div>
            <div class="uk-margin-medium">
                <button class="uk-button uk-button-primary uk-box-shadow-hover-large">
                    Log in <span class="uk-margin-small-left" uk-icon="arrow-right"></span>
                </button>
                <span class="uk-margin-small-left uk-margin-small-right">or</span> 
                <a href="{{ route('home') }}">Back to home</a>
            </div>
        </form>

    </div>

@endsection
