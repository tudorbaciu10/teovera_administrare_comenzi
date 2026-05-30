<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>{{ $title ?? __('comanda.titlu') }} — Teovera</title>
    <link rel="stylesheet" href="{{ asset('assets/css/comanda.css') }}">
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
    <script src="{{ asset('assets/js/comanda.js') }}"></script>
</body>
</html>
