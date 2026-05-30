@extends('layouts.admin')
@section('title', __('admin.magazine_titlu'))
@section('page-title', __('admin.magazine_titlu'))

@section('content')
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('admin.magazine_titlu') }}</span>
        <a href="{{ route('stores.create') }}" class="btn-primary btn-sm">+ {{ __('admin.btn_creeaza') }}</a>
    </div>
    <div class="card-body">
        <div class="table-wrap">
            <table class="data-table simple-table" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('admin.col_denumire') }}</th>
                        <th>{{ __('admin.col_localitate') }}</th>
                        <th>{{ __('admin.col_ruta') }}</th>
                        <th>{{ __('admin.col_tip') }}</th>
                        <th>{{ __('admin.link_comanda') }}</th>
                        <th>{{ __('admin.col_actiuni') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stores as $store)
                    <tr>
                        <td><strong>{{ $store->denumire }}</strong></td>
                        <td>{{ $store->localitate }}</td>
                        <td>{{ $store->route?->nume ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $store->tip === 'angro' ? 'badge-trimisa' : 'badge-printata' }}">
                                {{ __('admin.tip_'.$store->tip) }}
                            </span>
                        </td>
                        <td>
                            <div class="link-comanda-wrap" style="max-width:260px">
                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1">
                                    /comanda/{{ $store->token_acces }}
                                </span>
                                <button id="copyLinkBtn"
                                        data-url="{{ url('/comanda/'.$store->token_acces) }}"
                                        class="btn-icon btn-sm"
                                        style="flex-shrink:0;border:none;background:transparent;cursor:pointer"
                                        title="{{ __('admin.btn_copiaza_link') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('stores.edit', $store) }}" class="btn-icon" title="{{ __('admin.btn_editeaza') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('stores.regenerate-token', $store) }}">
                                    @csrf
                                    <button type="submit" class="btn-icon" title="{{ __('admin.btn_regenereaza') }}"
                                            onclick="return confirm('Regenerezi tokenul? Linkul vechi nu va mai funcționa!')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('stores.destroy', $store) }}" class="form-delete" data-confirm="{{ __('admin.confirmare_stergere') }}">
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
