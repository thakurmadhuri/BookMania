@extends('layouts.app')

@section('head')
<style>
.invalid-feedback {
    display: block !important;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: var(--bs-form-invalid-color);
}
</style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" id="register-form" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">
                                <span class="invalid-feedback" id="password-errors" role="alert">
                                    <strong> </strong>
                                </span>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong> {{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    var passwordInput = $('#password');
    var errorContainer = $('#password-errors');
    var err = document.getElementById('password-errors');

    function validatePassword(password) {
        var errors = [];

        if (!password) {
            errors.push('Password is required.');
        } else {
            if (password.length < 8) {
                errors.push('Password must be at least 8 characters long.');
            }
            if (!/[a-z]/.test(password)) {
                errors.push('Password must contain at least one lowercase letter.');
            }
            if (!/[A-Z]/.test(password)) {
                errors.push('Password must contain at least one uppercase letter.');
            }
            if (!/\d/.test(password)) {
                errors.push('Password must contain at least one digit.');
            }
            if (!/[_@#$%^&*!]/.test(password)) {
                errors.push("Password must contain at least one special character.");
            }
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    function displayErrors(errors) {
        err.innerHTML = "";
        $.each(errors, function(_, error) {
            err.innerHTML += '<strong>' + error + '</span><br>';
        });
        errorContainer.css('display', 'block');
    }

    passwordInput.on('blur', function() {
        var password = passwordInput.val().trim();
        var validationResult = validatePassword(password);
        if (!validationResult.isValid) {
            displayErrors(validationResult.errors);
        } else {
            errorContainer.empty();
        }
    });
});
</script>
@endsection