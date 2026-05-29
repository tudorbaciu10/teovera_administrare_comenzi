<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Comenzi multiple — {{ now()->format('d.m.Y') }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #111; background: #fff; }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 15mm 10mm;
            page-break-after: always;
        }
        .page:last-child { page-break-after: auto; }

        .header { display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #c0392b; padding-bottom:8px; margin-bottom:12px; }
        .header-brand { font-size:18px; font-weight:bold; color:#c0392b; }
        .header-sub { font-size:10px; color:#666; margin-top:2px; }
        .header-meta { text-align:right; font-size:11px; color:#444; }

        .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:4px 16px; background:#f8f8f8; border:1px solid #ddd; border-radius:4px; padding:10px 12px; margin-bottom:14px; font-size:12px; }
        .info-grid .label { color:#666; }
        .info-grid .value { font-weight:bold; }

        table { width:100%; border-collapse:collapse; margin-bottom:12px; }
        thead th { background:#c0392b; color:#fff; padding:7px 10px; text-align:left; font-size:11px; font-weight:bold; }
        thead th:last-child { text-align:right; }
        tbody tr:nth-child(even) { background:#fafafa; }
        tbody td { padding:7px 10px; border-bottom:1px solid #eee; }
        tbody td:last-child { text-align:right; font-weight:bold; font-size:14px; }
        .total-row td { font-weight:bold; background:#f0f0f0; border-top:2px solid #bbb; }

        .obs-box { border:1px solid #f0c040; background:#fffbea; border-radius:4px; padding:8px 12px; font-size:12px; margin-bottom:12px; }
        .signatures { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-top:20px; }
        .signature-block { border-top:1px solid #999; padding-top:6px; font-size:10px; color:#555; }
        .footer { margin-top:auto; padding-top:10px; border-top:1px solid #ddd; font-size:9px; color:#aaa; display:flex; justify-content:space-between; }

        .cat-badge { display:inline-block; font-size:9px; padding:1px 5px; border-radius:3px; background:#eee; color:#555; margin-left:4px; }

        @media print {
            body { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
            .no-print { display:none !important; }
        }
        @page { size: A4; margin: 0; }
    </style>
</head>
<body>

<div class="no-print" style="background:#1f2937;padding:10px 20px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:10;">
    <button onclick="window.print()"
            style="background:#c0392b;color:#fff;border:none;padding:8px 20px;border-radius:6px;font-size:14px;font-weight:bold;cursor:pointer;">
        🖨 Printează toate ({{ $orders->count() }})
    </button>
    <span style="color:#9ca3af;font-size:13px;">{{ $orders->count() }} comenzi · {{ now()->format('d.m.Y') }}</span>
    <button onclick="window.close()"
            style="background:transparent;color:#9ca3af;border:1px solid #374151;padding:8px 16px;border-radius:6px;font-size:13px;cursor:pointer;margin-left:auto;">
        Închide
    </button>
</div>

@foreach ($orders as $order)
<div class="page">

    <div class="header">
        <div>
            <div class="header-brand">Avicola-Teovera</div>
            <div class="header-sub">Sistem de preluare comenzi interne</div>
        </div>
        <div class="header-meta">
            <div style="font-size:14px;font-weight:bold;">COMANDĂ</div>
            <div>Nr. #{{ $order->id }}</div>
            <div>{{ $order->data->format('d.m.Y') }}</div>
        </div>
    </div>

    <div class="info-grid">
        <div><span class="label">Magazin:</span></div>
        <div><span class="value">{{ $order->store->denumire }}</span></div>
        <div><span class="label">Adresa:</span></div>
        <div><span class="value">{{ $order->store->adresa ?? $order->store->localitate }}</span></div>
        <div><span class="label">Rută:</span></div>
        <div><span class="value">{{ $order->route?->nume ?? '—' }}</span></div>
        <div><span class="label">Vânzătoare:</span></div>
        <div><span class="value">{{ $order->user?->name ?? '—' }}</span></div>
        <div><span class="label">Trimisă la:</span></div>
        <div><span class="value">{{ $order->created_at->format('d.m.Y H:i') }}</span></div>
        <div><span class="label">Status:</span></div>
        <div><span class="value" style="color:#c0392b;">
            {{ match($order->status) { 'trimisa' => 'TRIMISĂ', 'printata' => 'PRINTATĂ', 'livrata' => 'LIVRATĂ', default => strtoupper($order->status) } }}
        </span></div>
    </div>

    @php $items = $order->items->sortBy('product.category.ordine_sortare'); @endphp

    <table>
        <thead>
            <tr>
                <th style="width:32px;">#</th>
                <th>Produs</th>
                <th style="width:100px;">Categorie</th>
                <th style="width:80px;text-align:right;">Cantitate</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i => $item)
            <tr>
                <td style="color:#999;font-size:11px;">{{ $i + 1 }}</td>
                <td>{{ $item->product->nume }}</td>
                <td><span class="cat-badge">{{ $item->product->category->nume }}</span></td>
                <td>
                    {{ rtrim(rtrim(number_format((float)$item->cantitate, 3, '.', ''), '0'), '.') }}
                    <span style="font-size:10px;font-weight:normal;color:#666;">{{ $item->product->unitate }}</span>
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total articole</td>
                <td>{{ $items->count() }}</td>
            </tr>
        </tbody>
    </table>

    @if ($order->observatii)
    <div class="obs-box"><strong>Observații:</strong> {{ $order->observatii }}</div>
    @endif

    <div class="signatures">
        <div class="signature-block">Operator (preluat)</div>
        <div class="signature-block">Șofer (livrat)</div>
        <div class="signature-block">Vânzătoare (primit)</div>
    </div>

    <div class="footer">
        <span>Avicola-Teovera · Generat automat</span>
        <span>{{ now()->format('d.m.Y H:i') }}</span>
    </div>

</div>
@endforeach

</body>
</html>
