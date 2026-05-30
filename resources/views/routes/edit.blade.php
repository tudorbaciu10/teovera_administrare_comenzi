@extends('layouts.admin')
@section('title', __('admin.btn_editeaza').' — '.__('admin.rute_titlu'))
@section('page-title', __('admin.rute_titlu'))

@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header">
        <span class="card-title">{{ __('admin.btn_editeaza') }}: {{ $route->nume }}</span>
        <a href="{{ route('routes.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('routes.update', $route) }}">
            @csrf @method('PUT')
            <div class="form-group {{ $errors->has('nume') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_denumire') }}</label>
                <input type="text" name="nume" value="{{ old('nume', $route->nume) }}">
                @error('nume')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group {{ $errors->has('zile_livrare') ? 'has-error' : '' }}">
                <label>{{ __('admin.col_zile_livrare') }}</label>
                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:4px">
                    @foreach($zile as $zi)
                    @php $checked = in_array($zi, old('zile_livrare', $route->zile_livrare ?? [])); @endphp
                    <label style="font-weight:400;display:flex;align-items:center;gap:4px;cursor:pointer">
                        <input type="checkbox" name="zile_livrare[]" value="{{ $zi }}" {{ $checked ? 'checked' : '' }}>
                        {{ ucfirst($zi) }}
                    </label>
                    @endforeach
                </div>
                @error('zile_livrare')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-row">
                <div class="form-group {{ $errors->has('zi_cutoff') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_zi_cutoff') }}</label>
                    <select name="zi_cutoff">
                        @foreach($zile as $zi)
                            <option value="{{ $zi }}" {{ old('zi_cutoff', $route->zi_cutoff) === $zi ? 'selected' : '' }}>{{ ucfirst($zi) }}</option>
                        @endforeach
                    </select>
                    @error('zi_cutoff')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group {{ $errors->has('ora_cutoff') ? 'has-error' : '' }}">
                    <label>{{ __('admin.col_ora_cutoff') }}</label>
                    <input type="time" name="ora_cutoff" value="{{ old('ora_cutoff', substr($route->ora_cutoff, 0, 5)) }}">
                    @error('ora_cutoff')<span class="field-error">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">{{ __('admin.btn_salveaza') }}</button>
                <a href="{{ route('routes.index') }}" class="btn-secondary">{{ __('admin.btn_anuleaza') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
