<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panou') — Teovera</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    @stack('styles')
</head>
<body>

<div class="admin-wrap">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Conținut principal --}}
    <div style="flex:1">

        {{-- Topbar --}}
        @include('partials.topbar')

        {{-- Pagina --}}
        <main class="main-content">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>

    </div>
</div>

{{-- jQuery + DataTables --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets/js/admin.js') }}"></script>
@stack('scripts')

</body>
</html>
