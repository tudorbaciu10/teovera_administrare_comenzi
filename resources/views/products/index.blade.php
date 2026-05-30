@extends('layouts.admin')

@section('title', __('admin.produse_titlu'))
@section('page-title', __('admin.produse_titlu'))

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('admin.produse_titlu') }}</span>
        <a href="{{ route('products.create') }}" class="btn-primary btn-sm">+ {{ __('admin.btn_creeaza') }}</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table class="data-table simple-table" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('admin.col_id') }}</th>
                        <th>{{ __('admin.col_nume_ro') }}</th>
                        <th>{{ __('admin.col_nume_ru') }}</th>
                        <th>{{ __('admin.col_categorie') }}</th>
                        <th>{{ __('admin.col_unitate') }}</th>
                        <th>{{ __('admin.col_activ') }}</th>
                        <th>{{ __('admin.col_actiuni') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->nume_ro }}</td>
                        <td>{{ $product->nume_ru }}</td>
                        <td>{{ $product->category?->nume_ro }}</td>
                        <td>{{ $product->unitate }}</td>
                        <td>
                            @if($product->activ)
                                <span class="badge badge-printata">{{ __('admin.activ_da') }}</span>
                            @else
                                <span class="badge" style="background:#fee2e2;color:#b91c1c">{{ __('admin.activ_nu') }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('products.edit', $product) }}" class="btn-icon" title="{{ __('admin.btn_editeaza') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('products.destroy', $product) }}" class="form-delete" data-confirm="{{ __('admin.confirmare_stergere') }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
