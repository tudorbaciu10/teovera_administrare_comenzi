@extends('layouts.admin')

@section('title', __('admin.btn_creeaza').' — '.__('admin.produse_titlu'))
@section('page-title', __('admin.produse_titlu'))

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">{{ __('admin.btn_creeaza') }} produs</span>
        <a href="{{ route('products.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="form-row">
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
            </div>
            <div class="form-row">
                <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_categorie') }}</label>
                    <select name="category_id">
                        <option value="">— Selectează —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nume_ro }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group {{ $errors->has('unitate') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_unitate') }}</label>
                    <select name="unitate">
                        <option value="kg"  {{ old('unitate','kg') === 'kg'  ? 'selected' : '' }}>kg</option>
                        <option value="buc" {{ old('unitate') === 'buc' ? 'selected' : '' }}>buc</option>
                    </select>
                    @error('unitate')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="flex items-center gap-2" style="font-weight:400;cursor:pointer">
                    <input type="checkbox" name="activ" value="1" {{ old('activ', '1') ? 'checked' : '' }}>
                    {{ __('admin.col_activ') }}
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ __('admin.btn_salveaza') }}</button>
                <a href="{{ route('products.index') }}" class="btn-secondary">{{ __('admin.btn_anuleaza') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
