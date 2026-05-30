@extends('layouts.admin')
@section('title', __('admin.btn_creeaza').' — '.__('admin.magazine_titlu'))
@section('page-title', __('admin.magazine_titlu'))

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">{{ __('admin.btn_creeaza') }} magazin</span>
        <a href="{{ route('stores.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('stores.store') }}">
            @csrf
            <div class="form-group {{ $errors->has('denumire') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_denumire') }}</label>
                <input type="text" name="denumire" value="{{ old('denumire') }}">
                @error('denumire')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-row">
                <div class="form-group {{ $errors->has('localitate') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_localitate') }}</label>
                    <input type="text" name="localitate" value="{{ old('localitate') }}">
                    @error('localitate')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group {{ $errors->has('tip') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_tip') }}</label>
                    <select name="tip">
                        <option value="magazin" {{ old('tip','magazin') === 'magazin' ? 'selected' : '' }}>{{ __('admin.tip_magazin') }}</option>
                        <option value="angro"   {{ old('tip') === 'angro' ? 'selected' : '' }}>{{ __('admin.tip_angro') }}</option>
                    </select>
                    @error('tip')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-group {{ $errors->has('adresa') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_adresa') }}</label>
                <input type="text" name="adresa" value="{{ old('adresa') }}">
                @error('adresa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group {{ $errors->has('route_id') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_ruta') }}</label>
                <select name="route_id">
                    <option value="">— Fără rută —</option>
                    @foreach($routes as $route)
                        <option value="{{ $route->id }}" {{ old('route_id') == $route->id ? 'selected' : '' }}>{{ $route->nume }}</option>
                    @endforeach
                </select>
                @error('route_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ __('admin.btn_salveaza') }}</button>
                <a href="{{ route('stores.index') }}" class="btn-secondary">{{ __('admin.btn_anuleaza') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
