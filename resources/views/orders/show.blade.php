@extends('layouts.admin')

@section('title', __('admin.detalii_comanda'))
@section('page-title', __('admin.detalii_comanda'))

@section('content')

<div class="card">
    <div class="card-header">
        <span class="card-title">{{ __('admin.comanda_nr', ['id' => $order->id]) }}</span>
        <div class="flex gap-2 items-center">
            <span class="badge badge-{{ $order->status }}">{{ __('admin.status_'.$order->status) }}</span>
            <a href="{{ route('print.order', $order) }}" class="btn-secondary btn-sm" target="_blank">
                {{ __('admin.btn_printeaza') }}
            </a>
            <a href="{{ route('orders.index') }}" class="btn-secondary btn-sm">{{ __('admin.btn_back') }}</a>
        </div>
    </div>

    <div class="card-body">

        <div class="order-detail-grid">
            <div>
                <div class="order-detail-label">{{ __('admin.col_magazin') }}</div>
                <div class="order-detail-value">{{ $order->store?->denumire }}</div>
            </div>
            <div>
                <div class="order-detail-label">{{ __('admin.col_ruta') }}</div>
                <div class="order-detail-value">{{ $order->route?->nume }}</div>
            </div>
            <div>
                <div class="order-detail-label">{{ __('admin.col_data') }}</div>
                <div class="order-detail-value">{{ $order->data?->format('d.m.Y') }}</div>
            </div>
            <div>
                <div class="order-detail-label">{{ __('admin.col_vanzatoare') }}</div>
                <div class="order-detail-value">{{ $order->user?->name ?? '—' }}</div>
            </div>
            @if($order->observatii)
            <div style="grid-column: 1/-1">
                <div class="order-detail-label">Observații</div>
                <div class="order-detail-value">{{ $order->observatii }}</div>
            </div>
            @endif
        </div>

        {{-- Produse grupate pe categorie --}}
        @php
            $byCategory = $order->items->sortBy([
                fn ($a, $b) => $a->product->category->ordine_sortare <=> $b->product->category->ordine_sortare,
                fn ($a, $b) => $a->product->nume_ro <=> $b->product->nume_ro,
            ])->groupBy(fn ($item) => $item->product->category->nume);
        @endphp

        @foreach($byCategory as $catName => $items)
            <h4 style="font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--text-muted); margin: 16px 0 8px">
                {{ $catName }}
            </h4>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>{{ __('admin.col_nume') }}</th>
                            <th style="width:120px">{{ __('admin.col_unitate') }}</th>
                            <th style="width:120px">Cantitate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->product->nume }}</td>
                            <td>{{ $item->product->unitate }}</td>
                            <td><strong>{{ rtrim(rtrim(number_format($item->cantitate, 3, '.', ''), '0'), '.') }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        {{-- Acțiuni status --}}
        <div class="form-actions mt-3">
            @if($order->status === 'trimisa')
                <form method="POST" action="{{ route('orders.mark-printata', $order) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-primary">✓ {{ __('admin.status_printata') }}</button>
                </form>
            @elseif($order->status === 'printata')
                <form method="POST" action="{{ route('orders.mark-livrata', $order) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-primary">✓ {{ __('admin.status_livrata') }}</button>
                </form>
            @endif

            @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('orders.destroy', $order) }}"
                      class="form-delete"
                      data-confirm="{{ __('admin.confirmare_stergere') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">{{ __('admin.btn_sterge') }}</button>
                </form>
            @endif
        </div>

    </div>
</div>

@endsection
