<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>{{ $title ?? 'Teovera Comenzi' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { DEFAULT: '#c0392b', dark: '#922b21' }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { opacity: 1; }
    </style>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    <header class="bg-brand text-white px-4 py-3 shadow sticky top-0 z-10">
        <div class="flex items-center gap-2">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.3 2.3A1 1 0 006 17h12M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
            </svg>
            <div>
                <div class="text-xs opacity-75 leading-none">Avicola-Teovera</div>
                <div class="font-semibold leading-tight">{{ $title ?? 'Comandă' }}</div>
            </div>
        </div>
    </header>

    <main class="max-w-lg mx-auto pb-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
