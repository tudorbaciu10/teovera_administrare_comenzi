<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda #{{ $order->id }} — {{ $order->store?->denumire }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}">
</head>
<body>

<div class="no-print" style="display:flex;gap:8px;padding:12px">
    <button onclick="window.print()" style="padding:6px 16px;background:#2563eb;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px">
        Printează
    </button>
    <a href="{{ route('print.order.pdf', $order) }}"
       style="padding:6px 16px;background:#fff;color:#333;border:1px solid #ccc;border-radius:4px;cursor:pointer;font-size:13px;text-decoration:none">
        PDF
    </a>
    <button onclick="window.close()" style="padding:6px 16px;background:#fff;color:#333;border:1px solid #ccc;border-radius:4px;cursor:pointer;font-size:13px">
        Închide
    </button>
</div>

<div class="order-page">

    {{-- Header --}}
    <div class="print-header">
        <div>
            <div class="print-brand">Avicola-Teovera</div>
            <div class="print-brand-sub">Comandă magazin</div>
        </div>
        <div class="print-order-nr">
            <div>Comanda</div>
            <strong>#{{ $order->id }}</strong>
            <div style="font-size:8pt;color:#666;margin-top:4px">
                {{ $order->created_at->format('d.m.Y H:i') }}
            </div>
        </div>
    </div>

    {{-- Info --}}
    <div class="print-info-grid">
        <div>
            <div class="print-info-label">Magazin</div>
            <div class="print-info-value">{{ $order->store?->denumire }}</div>
        </div>
        <div>
            <div class="print-info-label">Rută</div>
            <div class="print-info-value">{{ $order->route?->nume }}</div>
        </div>
        <div>
            <div class="print-info-label">Adresă</div>
            <div class="print-info-value" style="font-weight:400">{{ $order->store?->adresa }}, {{ $order->store?->localitate }}</div>
        </div>
        <div>
            <div class="print-info-label">Data livrare</div>
            <div class="print-info-value">{{ $order->data?->format('d.m.Y') }}</div>
        </div>
        <div>
            <div class="print-info-label">Vânzătoare</div>
            <div class="print-info-value" style="font-weight:400">{{ $order->user?->name ?? '—' }}</div>
        </div>
        <div>
            <div class="print-info-label">Status</div>
            <div class="print-info-value">{{ ucfirst($order->status) }}</div>
        </div>
    </div>

    {{-- Produse grupate pe categorie --}}
    @php
        $byCategory = $order->items->sortBy([
            fn ($a, $b) => $a->product->category->ordine_sortare <=> $b->product->category->ordine_sortare,
            fn ($a, $b) => $a->product->nume_ro <=> $b->product->nume_ro,
        ])->groupBy(fn ($item) => $item->product->category->ordine_sortare.'|'.$item->product->category->nume_ro);
    @endphp

    @foreach($byCategory as $catKey => $items)
        @php $catName = explode('|', $catKey)[1] ?? $catKey; @endphp
        <div class="print-category">{{ $catName }}</div>
        <table class="print-table">
            <thead>
                <tr>
                    <th>Produs</th>
                    <th class="qty-cell">Cantitate</th>
                    <th class="unit-cell">U.M.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->product->nume_ro }}</td>
                    <td class="qty-cell">{{ rtrim(rtrim(number_format($item->cantitate, 3, '.', ''), '0'), '.') }}</td>
                    <td class="unit-cell">{{ $item->product->unitate }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- Observații --}}
    @if($order->observatii)
    <div class="print-obs">
        <div class="print-obs-label">Observații</div>
        {{ $order->observatii }}
    </div>
    @endif

    {{-- Semnături --}}
    <div class="print-signatures">
        <div class="print-sig-line">Operator</div>
        <div class="print-sig-line">Șofer</div>
        <div class="print-sig-line">Vânzătoare</div>
    </div>

</div>

</body>
</html>
