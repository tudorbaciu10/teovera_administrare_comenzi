<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.titlu') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-logo">
        <span class="login-brand">Avicola-Teovera</span>
    </div>

    <form method="POST" action="{{ route('login.post') }}" class="login-form">
        @csrf

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">{{ __('auth.email') }}</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   autocomplete="email" autofocus>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <label for="password">{{ __('auth.parola') }}</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group form-check">
            <label>
                <input type="checkbox" name="remember" value="1">
                {{ __('auth.tine_minte') }}
            </label>
        </div>

        <button type="submit" class="btn-primary btn-full">{{ __('auth.btn_login') }}</button>
    </form>

    <div class="login-lang">
        <a href="{{ route('lang.switch', 'ro') }}" class="{{ app()->getLocale() === 'ro' ? 'active' : '' }}">RO</a>
        <span>|</span>
        <a href="{{ route('lang.switch', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
    </div>
</div>

</body>
</html>
