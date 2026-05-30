@extends('layouts.admin')

@section('title', __('admin.comenzi_titlu'))
@section('page-title', __('admin.comenzi_titlu'))

@section('content')

<div class="card orders-page-card" id="ordersCard">
    <div class="card-header orders-page-header" id="ordersCardHeader">
        <span class="card-title orders-page-title" id="ordersPageTitle">{{ __('admin.comenzi_titlu') }}</span>
        <div id="newOrdersBadge" class="badge badge-trimisa orders-new-badge" style="display:none"></div>
    </div>

    <div class="card-body orders-page-body" id="ordersCardBody">

        {{-- Filtre --}}
        <form method="GET" action="{{ route('orders.index') }}" class="filters-bar orders-filters-form" id="filtersForm">
            <select id="filterRoute" name="route_id" class="filter-select filter-select--ruta">
                <option value="">{{ __('admin.filtru_toate_rutele') }}</option>
                @foreach($routes as $route)
                    <option value="{{ $route->id }}" {{ request('route_id') == $route->id ? 'selected' : '' }}>
                        {{ $route->nume }}
                    </option>
                @endforeach
            </select>

            {{-- Dată + butoane rapide --}}
            <div class="date-quick-wrap orders-date-wrap" id="ordersDateWrap">
                <input type="date" id="filterData" name="data" class="filter-input filter-input--data" value="{{ request('data', date('Y-m-d')) }}">
                <button type="button" class="btn-secondary btn-sm btn-date-azi" id="btnAzi">Azi</button>
                <button type="button" class="btn-secondary btn-sm btn-date-maine" id="btnMaine">Mâine</button>
            </div>

            <select name="status" id="filterStatus" class="filter-select filter-select--status">
                <option value="">{{ __('admin.filtru_toate_status') }}</option>
                <option value="trimisa"  {{ request('status') === 'trimisa'  ? 'selected' : '' }}>{{ __('admin.status_trimisa') }}</option>
                <option value="printata" {{ request('status') === 'printata' ? 'selected' : '' }}>{{ __('admin.status_printata') }}</option>
                <option value="livrata"  {{ request('status') === 'livrata'  ? 'selected' : '' }}>{{ __('admin.status_livrata') }}</option>
            </select>

            <button type="submit" class="btn-primary btn-sm btn-filters-apply" id="btnFiltersApply">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrează
            </button>

            <a href="{{ route('orders.index') }}" class="btn-secondary btn-sm btn-filters-reset" id="btnFiltersReset">Reset</a>

            @if(request('route_id') || request('data'))
                <a href="{{ route('print.orders.bulk', request()->only('route_id','data','status')) }}"
                   id="btnPrintRoute" class="btn-secondary btn-sm btn-print-ruta" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    {{ __('admin.btn_printeaza') }}
                </a>
            @endif
        </form>

        {{-- Panou rută selectată: magazine incluse --}}
        @if($selectedRoute)
        <div class="route-info-panel" id="routeInfoPanel">
            <div class="route-info-header" id="routeInfoHeader">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                <strong class="route-info-name">{{ $selectedRoute->nume }}</strong>
                <span class="route-info-count badge badge-trimisa">{{ $selectedRoute->stores->count() }} magazine</span>
            </div>
            <div class="route-stores-list" id="routeStoresList">
                @foreach($selectedRoute->stores->sortBy('denumire') as $routeStore)
                    <span class="route-store-chip" id="routeStoreChip{{ $routeStore->id }}">{{ $routeStore->denumire }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tabel --}}
        <div class="table-wrap orders-table-wrap" id="ordersTableWrap">
            <table id="ordersTable" class="data-table orders-main-table" style="width:100%">
                <thead class="orders-thead">
                    <tr class="orders-thead-row">
                        <th class="col-check orders-col-check">
                            <input type="checkbox" id="selectAll" class="select-all-checkbox" title="Selectează tot">
                        </th>
                        <th class="orders-col-id">{{ __('admin.col_id') }}</th>
                        <th class="orders-col-magazin">{{ __('admin.col_magazin') }}</th>
                        <th class="orders-col-ruta">{{ __('admin.col_ruta') }}</th>
                        <th class="orders-col-data">{{ __('admin.col_data') }}</th>
                        <th class="orders-col-vanzatoare">{{ __('admin.col_vanzatoare') }}</th>
                        <th class="orders-col-produse">{{ __('admin.col_produse') }}</th>
                        <th class="orders-col-status">{{ __('admin.col_status') }}</th>
                        <th class="orders-col-actiuni">{{ __('admin.col_actiuni') }}</th>
                    </tr>
                </thead>
                <tbody class="orders-tbody" id="ordersTableBody">
                    @foreach($orders as $order)
                    @php
                        $locale = app()->getLocale();
                        $prodData = $order->items
                            ->sortBy(fn($i) => $i->product?->category?->ordine_sortare)
                            ->map(fn($i) => [
                                'n' => $i->product?->{'nume_'.$locale} ?? $i->product?->nume_ro,
                                'q' => rtrim(rtrim(number_format($i->cantitate, 3, '.', ''), '0'), '.'),
                                'u' => $i->product?->unitate,
                            ])->values()->toArray();
                    @endphp
                    <tr class="order-row orders-table-row" id="orderRow{{ $order->id }}" data-order-id="{{ $order->id }}">
                        <td class="col-check orders-col-check">
                            <input type="checkbox" class="order-check order-row-checkbox" value="{{ $order->id }}" id="orderCheck{{ $order->id }}">
                        </td>
                        <td class="orders-cell-id">#{{ $order->id }}</td>
                        <td class="orders-cell-magazin">
                            <strong class="store-name">{{ $order->store?->denumire }}</strong>
                            @if($order->store?->localitate)
                                <br><span class="text-muted text-sm store-localitate">{{ $order->store->localitate }}</span>
                            @endif
                        </td>
                        <td class="orders-cell-ruta">{{ $order->route?->nume }}</td>
                        <td class="orders-cell-data">{{ $order->data?->format('d.m.Y') }}</td>
                        <td class="orders-cell-vanzatoare">{{ $order->user?->name }}</td>
                        <td class="cell-produse orders-cell-produse" data-products="{{ e(json_encode($prodData)) }}">
                            <span class="prod-count orders-prod-count">{{ $order->items->count() }} produse</span>
                        </td>
                        <td class="orders-cell-status">
                            <span class="badge badge-{{ $order->status }} order-status-badge">
                                {{ __('admin.status_'.$order->status) }}
                            </span>
                        </td>
                        <td class="col-actions orders-cell-actiuni">
                            <div class="row-actions order-row-actions">
                                <a href="{{ route('orders.show', $order) }}" class="btn-icon btn-icon--detalii" title="Detalii">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('print.order', $order) }}" class="btn-icon btn-icon--print" title="{{ __('admin.btn_printeaza') }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>

                                @if($order->status === 'trimisa')
                                    <form method="POST" action="{{ route('orders.mark-printata', $order) }}" class="form-status-update form-mark-printata">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon btn-icon--mark-printata" title="{{ __('admin.status_printata') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </form>
                                @elseif($order->status === 'printata')
                                    <form method="POST" action="{{ route('orders.mark-livrata', $order) }}" class="form-status-update form-mark-livrata">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon btn-icon--mark-livrata" title="{{ __('admin.status_livrata') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}"
                                          class="form-delete form-delete-order"
                                          data-confirm="{{ __('admin.confirmare_stergere') }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-danger btn-icon--sterge" title="{{ __('admin.btn_sterge') }}">
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

        {{-- Export + Bulk print --}}
        <div class="export-bar orders-export-bar" id="ordersExportBar">
            <div class="bulk-bar orders-bulk-bar is-empty" id="bulkBar">
                <span id="bulkCount" class="badge badge-trimisa bulk-count-badge">0 selectate</span>
                <button type="button" id="btnPrintSelected" class="btn-primary btn-sm btn-bulk-print">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Printează selectate
                </button>
            </div>
            <a href="{{ route('orders.export-csv', request()->query()) }}" class="btn-secondary btn-sm btn-export-csv" id="btnExportCsv">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('admin.btn_exporta_csv') }}
            </a>
            <a href="{{ route('orders.export-pdf', request()->query()) }}" class="btn-secondary btn-sm btn-export-pdf" id="btnExportPdf" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                {{ __('admin.btn_exporta_pdf') }}
            </a>
        </div>

    </div>
</div>

@endsection
