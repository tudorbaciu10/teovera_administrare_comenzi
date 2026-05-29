<div class="space-y-4">

    {{-- Info comandă --}}
    <div class="grid grid-cols-2 gap-2 text-sm">
        <div>
            <span class="text-gray-500">Magazin:</span>
            <span class="font-medium ml-1">{{ $order->store->denumire }}</span>
        </div>
        <div>
            <span class="text-gray-500">Localitate:</span>
            <span class="font-medium ml-1">{{ $order->store->localitate }}</span>
        </div>
        <div>
            <span class="text-gray-500">Rută:</span>
            <span class="font-medium ml-1">{{ $order->route?->nume ?? '—' }}</span>
        </div>
        <div>
            <span class="text-gray-500">Vânzătoare:</span>
            <span class="font-medium ml-1">{{ $order->user?->name ?? '—' }}</span>
        </div>
        <div>
            <span class="text-gray-500">Data:</span>
            <span class="font-medium ml-1">{{ $order->data->format('d.m.Y') }}</span>
        </div>
        <div>
            <span class="text-gray-500">Trimisă la:</span>
            <span class="font-medium ml-1">{{ $order->created_at->format('H:i') }}</span>
        </div>
    </div>

    {{-- Tabel produse --}}
    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="text-left py-2 px-3 font-semibold text-gray-600">Produs</th>
                <th class="text-left py-2 px-3 font-semibold text-gray-600">Categorie</th>
                <th class="text-right py-2 px-3 font-semibold text-gray-600">Cantitate</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($order->items->sortBy('product.category.ordine_sortare') as $item)
            <tr>
                <td class="py-2 px-3 font-medium">{{ $item->product->nume }}</td>
                <td class="py-2 px-3 text-gray-500">{{ $item->product->category->nume }}</td>
                <td class="py-2 px-3 text-right font-semibold tabular-nums">
                    {{ rtrim(rtrim(number_format((float)$item->cantitate, 3, '.', ''), '0'), '.') }}
                    <span class="text-gray-400 font-normal">{{ $item->product->unitate }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($order->observatii)
    <div class="bg-yellow-50 border border-yellow-200 rounded px-3 py-2 text-sm">
        <span class="font-medium text-yellow-800">Observații:</span>
        <span class="text-yellow-900 ml-1">{{ $order->observatii }}</span>
    </div>
    @endif

    {{-- Buton print --}}
    <div class="pt-2">
        <a href="{{ route('print.order', $order) }}" target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Printează această comandă
        </a>
    </div>

</div>
