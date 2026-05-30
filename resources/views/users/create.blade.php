@extends('layouts.admin')
@section('title', __('admin.btn_creeaza').' — '.__('admin.utilizatori_titlu'))
@section('page-title', __('admin.utilizatori_titlu'))

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">{{ __('admin.btn_creeaza') }} utilizator</span>
        <a href="{{ route('users.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label>Nume</label>
                    <input type="text" name="name" value="{{ old('name') }}">
                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label>Parolă</label>
                <input type="password" name="password">
                @error('password')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-row">
                <div class="form-group {{ $errors->has('rol') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_rol') }}</label>
                    <select name="rol">
                        <option value="vanzatoare" {{ old('rol','vanzatoare') === 'vanzatoare' ? 'selected' : '' }}>{{ __('admin.rol_vanzatoare') }}</option>
                        <option value="operator"   {{ old('rol') === 'operator' ? 'selected' : '' }}>{{ __('admin.rol_operator') }}</option>
                        <option value="admin"      {{ old('rol') === 'admin'    ? 'selected' : '' }}>{{ __('admin.rol_admin') }}</option>
                    </select>
                    @error('rol')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group {{ $errors->has('lang') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_limba') }}</label>
                    <select name="lang">
                        <option value="ro" {{ old('lang','ro') === 'ro' ? 'selected' : '' }}>Română (RO)</option>
                        <option value="ru" {{ old('lang') === 'ru' ? 'selected' : '' }}>Русский (RU)</option>
                    </select>
                    @error('lang')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-group {{ $errors->has('store_id') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_magazin') }} (pentru vânzătoare)</label>
                <select name="store_id">
                    <option value="">— Fără magazin —</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->denumire }}</option>
                    @endforeach
                </select>
                @error('store_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ __('admin.btn_salveaza') }}</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">{{ __('admin.btn_anuleaza') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
