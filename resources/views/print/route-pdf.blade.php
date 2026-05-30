<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    {{--
        dompdf NU citește @media print și NU încarcă CSS extern.
        Stilurile sunt INLINE. DejaVu Sans (inclus în dompdf) are
        diacriticele RO (ș ț ă î â) + chirilica → repară semnele de întrebare.
        dompdf NU suportă flexbox/grid/column-count → 2 coloane cu tabel.
    --}}
    <style>
        @page { margin: 8mm 10mm; }

        * { font-family: 'DejaVu Sans', sans-serif; }

        body {
            font-size: 10px;
            color: #000;
            line-height: 1.2;
        }

        .doc-title {
            font-size: 11px;
            font-weight: bold;
            border-bottom: 1.5px solid #000;
            padding-bottom: 3px;
            margin-bottom: 7px;
        }

        .order-block {
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .order-head-main {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 1px;
        }

        .order-head-sub {
            font-size: 8px;
            color: #333;
            margin-bottom: 2px;
        }

        /* ── Tabel produse ── */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .item-table th,
        .item-table td {
            padding: 1px 4px;
            border: 1px solid #999;
            font-size: 10px;
            line-height: 1.2;
        }

        .item-table thead th {
            background: #eee;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }

        .cat-row td {
            background: #f4f4f4;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #999;
        }

        .col-qty { text-align: right; font-weight: bold; width: 55px; }
        .col-um  { width: 40px; color: #444; }

        .order-note { font-size: 9px; margin: 2px 0 3px; }

        /* ── 2 coloane cu tabel (dompdf nu suportă flexbox/column-count) ── */
        .item-table-2col {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 2px;
        }

        .col-half {
            width: 50%;
            vertical-align: top;
        }

        .col-half-left {
            padding-right: 4px;
            border-right: 1px dotted #bbb;
        }

        .col-half-right {
            padding-left: 4px;
        }

        /* ── Separator punctat între comenzi ── */
        .order-separator {
            border-bottom: 1.5px dashed #333;
            margin: 6px 0;
        }

        /* ── Semnături (tabel, dompdf nu suportă flexbox) ── */
        .signatures {
            margin-top: 14px;
            width: 100%;
        }

        .signatures td {
            border-top: 1px solid #000;
            padding-top: 4px;
            font-size: 9px;
            text-align: center;
            width: 33%;
        }
    </style>
</head>
<body>

    <div class="doc-title">
        Avicola-Teovera &middot; {{ __('print.titlu_ruta') }}: <strong>{{ $route->nume }}</strong>
        &middot; {{ \Carbon\Carbon::parse($printDate)->format('d.m.Y') }}
        &middot; {{ $orders->count() }} {{ __('print.magazine') }}
    </div>

    @foreach ($orders as $i => $order)
        @include('print._order', [
            'order' => $order,
            'lang'  => $lang,
            'index' => $i + 1,
            'total' => $orders->count(),
        ])
        @if (! $loop->last)
            <div class="order-separator"></div>
        @endif
    @endforeach

    {{-- Semnături globale (tabel — compatibil dompdf) --}}
    <table class="signatures">
        <tr>
            <td>{{ __('print.operator') }}</td>
            <td>{{ __('print.sofer') }}</td>
            <td>{{ __('print.receptie') }}</td>
        </tr>
    </table>

</body>
</html>
