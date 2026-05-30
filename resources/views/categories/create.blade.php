@extends('layouts.admin')
@section('title', __('admin.btn_creeaza').' — '.__('admin.categorii_titlu'))
@section('page-title', __('admin.categorii_titlu'))

@section('content')
<div class="card" style="max-width:500px">
    <div class="card-header">
        <span class="card-title">{{ __('admin.btn_creeaza') }} categorie</span>
        <a href="{{ route('categories.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="form-group {{ $errors->has('nume_ro') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_nume_ro') }}</label>
                <input type="text" name="nume_ro" value="{{ old('nume_ro') }}">
                @error('nume_ro')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group {{ $errors->has('nume_ru') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_nume_ru') }}</label>
                <input type="text" name="nume_ru" value="{{ old('nume_ru') }}">
                @error('nume_ru')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group {{ $errors->has('ordine_sortare') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_ordine') }}</label>
                <input type="number" name="ordine_sortare" value="{{ old('ordine_sortare', 0) }}" min="0">
                @error('ordine_sortare')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ __('admin.btn_salveaza') }}</button>
                <a href="{{ route('categories.index') }}" class="btn-secondary">{{ __('admin.btn_anuleaza') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
