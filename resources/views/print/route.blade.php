<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('print.titlu_ruta') }} — {{ $route->nume }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}">
</head>
<body class="print-page">

    <div class="print-toolbar no-print">
        <button onclick="window.print()" class="btn-print">{{ __('print.btn_printeaza') }}</button>
        <button onclick="window.close()" class="btn-close">{{ __('print.btn_inchide') }}</button>
        <a href="{{ route('print.route.pdf', ['route' => $route->id, 'data' => request('data')]) }}" class="btn-pdf">PDF</a>
    </div>

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

    <div class="signatures">
        <div class="sign-cell">{{ __('print.operator') }}</div>
        <div class="sign-cell">{{ __('print.sofer') }}</div>
        <div class="sign-cell">{{ __('print.receptie') }}</div>
    </div>

</body>
</html>
