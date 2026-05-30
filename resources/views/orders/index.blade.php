@extends('layouts.admin')

@section('title', __('admin.comenzi_titlu'))
@section('page-title', __('admin.comenzi_titlu'))

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('admin.comenzi_titlu') }}</span>
        <div id="newOrdersBadge" class="badge badge-trimisa" style="display:none"></div>
    </div>

    <div class="card-body">

        {{-- Filtre --}}
        <form method="GET" action="{{ route('orders.index') }}" class="filters-bar">
            <select id="routeFilter" name="route_id">
                <option value="">{{ __('admin.filtru_toate_rutele') }}</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                        {{ $route->nume }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="data" value="{{ request('data', date('Y-m-d')) }}">

            <select name="status">
                <option value="">{{ __('admin.filtru_toate_status') }}</option>
                <option value="trimisa"  {{ request('status') === 'trimisa'  ? 'selected' : '' }}>{{ __('admin.status_trimisa') }}</option>
                <option value="printata" {{ request('status') === 'printata' ? 'selected' : '' }}>{{ __('admin.status_printata') }}</option>
                <option value="livrata"  {{ request('status') === 'livrata'  ? 'selected' : '' }}>{{ __('admin.status_livrata') }}</option>
            </select>

            <button type="submit" class="btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrează
            </button>

            <a href="{{ route('orders.index') }}" class="btn-secondary btn-sm">Reset</a>

            {{-- Printare rută --}}
            @if(request('route_id') || request('data'))
                <a href="{{ route('print.orders.bulk', request()->only('route_id','data','status')) }}"
                   id="btnPrintRoute"
                   class="btn-secondary btn-sm"
                   target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    {{ __('admin.btn_printeaza') }}
                </a>
            @endif
        </form>

        {{-- Tabel --}}
        <div class="table-wrap">
            <table id="ordersTable" class="data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>{{ __('admin.col_id') }}</th>
                        <th>{{ __('admin.col_magazin') }}</th>
                        <th>{{ __('admin.col_ruta') }}</th>
                        <th>{{ __('admin.col_data') }}</th>
                        <th>{{ __('admin.col_vanzatoare') }}</th>
                        <th>{{ __('admin.col_produse') }}</th>
                        <th>{{ __('admin.col_status') }}</th>
                        <th>{{ __('admin.col_actiuni') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="order-row">
                        <td>#{{ $order->id }}</td>
                        <td>
                            <strong>{{ $order->store?->denumire }}</strong>
                            @if($order->store?->localitate)
                                <br><span class="text-muted text-sm">{{ $order->store->localitate }}</span>
                            @endif
                        </td>
                        <td>{{ $order->route?->nume }}</td>
                        <td>{{ $order->data?->format('d.m.Y') }}</td>
                        <td>{{ $order->user?->name }}</td>
                        <td>
                            <span class="text-muted text-sm">{{ $order->items->count() }} produse</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->status }}">
                                {{ __('admin.status_'.$order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                {{-- Detalii --}}
                                <a href="{{ route('orders.show', $order) }}" class="btn-icon" title="Detalii">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                {{-- Print --}}
                                <a href="{{ route('print.order', $order) }}" class="btn-icon" title="{{ __('admin.btn_printeaza') }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>

                                {{-- Status: marchează printată --}}
                                @if($order->status === 'trimisa')
                                    <form method="POST" action="{{ route('orders.mark-printata', $order) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon" title="{{ __('admin.status_printata') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                @elseif($order->status === 'printata')
                                    <form method="POST" action="{{ route('orders.mark-livrata', $order) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon" title="{{ __('admin.status_livrata') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                @endif

                                {{-- Șterge (doar admin) --}}
                                @if(auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}"
                                          class="form-delete"
                                          data-confirm="{{ __('admin.confirmare_stergere') }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-danger" title="{{ __('admin.btn_sterge') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Export --}}
        <div class="export-bar">
            <a href="{{ route('orders.export-csv', request()->query()) }}" class="btn-secondary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('admin.btn_exporta_csv') }}
            </a>
            <a href="{{ route('orders.export-pdf', request()->query()) }}" class="btn-secondary btn-sm" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                {{ __('admin.btn_exporta_pdf') }}
            </a>
        </div>

    </div>
</div>

@endsection
