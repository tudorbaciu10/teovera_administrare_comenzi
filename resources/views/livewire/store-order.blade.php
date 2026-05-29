{{-- ════════════════════════════════════════════════════════════════
     ECRANUL DE SUCCES — comanda a fost trimisă
     ════════════════════════════════════════════════════════════════ --}}
@if ($submitted && $order)

<div class="px-4 pt-6 space-y-4">

    {{-- Banner succes --}}
    <div class="bg-green-50 border border-green-300 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-6 h-6 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <div>
            <div class="font-semibold text-green-800">Comanda a fost trimisă!</div>
            <div class="text-sm text-green-700">
                {{ $order->data->format('d.m.Y') }}
                @if($order->user)
                    · {{ $order->user->name }}
                @endif
            </div>
        </div>
    </div>

    {{-- Rezumat produse --}}
    <div class="bg-white rounded-xl shadow-sm divide-y divide-gray-100 overflow-hidden">
        @foreach ($order->load('items.product.category')->items->sortBy('product.category.ordine_sortare') as $item)
        <div class="flex justify-between items-center px-4 py-3">
            <div>
                <div class="text-sm font-medium">{{ $item->product->nume }}</div>
                <div class="text-xs text-gray-400">{{ $item->product->category->nume }}</div>
            </div>
            <div class="font-semibold text-brand tabular-nums">
                {{ rtrim(rtrim(number_format((float)$item->cantitate, 3, '.', ''), '0'), '.') }}
                <span class="text-xs font-normal text-gray-500">{{ $item->product->unitate }}</span>
            </div>
        </div>
        @endforeach
    </div>

    @if ($order->observatii)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 text-sm text-yellow-800">
        <span class="font-medium">Obs:</span> {{ $order->observatii }}
    </div>
    @endif

    {{-- Buton editare (dacă nu am depășit cut-off-ul) --}}
    @if (! $cutoffPassed)
    <button wire:click="editOrder"
            class="w-full py-3 rounded-xl border-2 border-brand text-brand font-semibold text-sm
                   active:bg-red-50 transition-colors">
        Modifică comanda
    </button>
    @else
    <p class="text-center text-xs text-gray-400">Termenul de editare a expirat.</p>
    @endif

</div>

{{-- ════════════════════════════════════════════════════════════════
     BLOCAT — cut-off depășit, nicio comandă azi
     ════════════════════════════════════════════════════════════════ --}}
@elseif ($cutoffPassed && ! $order)

<div class="px-4 pt-10 text-center space-y-4">
    <div class="text-5xl">🔒</div>
    <h2 class="text-lg font-semibold text-gray-700">Termenul a expirat</h2>
    <p class="text-sm text-gray-500">
        Comanda pentru această rută nu mai poate fi trimisă astăzi.<br>
        Contactează operatorul dacă ai o urgență.
    </p>
    @if ($store->route)
    <p class="text-xs text-gray-400">
        Cut-off: {{ $store->route->zi_cutoff }} ora {{ substr($store->route->ora_cutoff, 0, 5) }}
    </p>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════════
     FORMULAR DE COMANDĂ
     ════════════════════════════════════════════════════════════════ --}}
@else

<div class="space-y-0">

    {{-- Info magazin + rută --}}
    <div class="px-4 pt-4 pb-2 bg-white shadow-sm mb-3">
        <p class="text-xs text-gray-500">
            {{ $store->localitate }}
            @if($store->route)
                · <span class="text-brand font-medium">{{ $store->route->nume }}</span>
            @endif
        </p>
        <p class="text-xs text-gray-400 mt-0.5">
            Data comenzii: <span class="font-medium">{{ now()->format('d.m.Y') }}</span>
        </p>
    </div>

    {{-- Erori globale --}}
    @if ($errorMsg)
    <div class="mx-4 mb-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
        {{ $errorMsg }}
    </div>
    @endif

    {{-- ── CATALOG ── --}}
    @foreach ($categories as $category)

    <div class="mb-1">
        <div class="px-4 py-2 bg-gray-100 sticky top-[60px] z-[5]">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">
                {{ $category->nume }}
            </span>
        </div>

        <div class="bg-white divide-y divide-gray-100">
        @foreach ($category->products as $product)
        <div class="flex items-center justify-between px-4 py-3 gap-3">

            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium leading-snug truncate">{{ $product->nume }}</div>
                <div class="text-xs text-gray-400">{{ $product->unitate }}</div>
            </div>

            <div class="flex items-center gap-1 shrink-0">
                {{-- Buton – --}}
                @php $step = $product->unitate === 'kg' ? 0.5 : 1; @endphp
                <button type="button"
                        wire:click="decrementQty('{{ $product->id }}', {{ $step }})"
                        class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center
                               text-lg leading-none active:bg-gray-200 touch-manipulation select-none">
                    −
                </button>

                {{-- Input cantitate --}}
                <input
                    type="number"
                    min="0"
                    step="{{ $step }}"
                    inputmode="decimal"
                    wire:model.lazy="quantities.{{ $product->id }}"
                    placeholder="0"
                    class="w-16 text-center border border-gray-200 rounded-lg py-1.5 text-sm font-semibold
                           focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand
                           {{ (float)($quantities[$product->id] ?? 0) > 0 ? 'border-brand bg-red-50 text-brand' : '' }}"
                >

                {{-- Buton + --}}
                <button type="button"
                        wire:click="incrementQty('{{ $product->id }}', {{ $step }})"
                        class="w-8 h-8 rounded-full bg-brand text-white flex items-center justify-center
                               text-lg leading-none active:bg-brand-dark touch-manipulation select-none">
                    +
                </button>
            </div>

        </div>
        @endforeach
        </div>
    </div>

    @endforeach

    {{-- ── FOOTER FORMULAR ── --}}
    <div class="px-4 pt-4 pb-6 space-y-4 bg-white mt-3 shadow-sm">

        {{-- Persoana care trimite --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Persoana care trimite *</label>
            <select wire:model="userId"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm
                           focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand
                           bg-white {{ !$userId ? 'text-gray-400' : 'text-gray-900' }}">
                <option value="">— selectează —</option>
                @foreach ($vanzatoare as $v)
                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Observații --}}
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Observații (opțional)</label>
            <textarea wire:model.lazy="observatii"
                      rows="2"
                      placeholder="Mențiuni speciale..."
                      class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm resize-none
                             focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand"></textarea>
        </div>

        {{-- Rezumat produse selectate --}}
        @php
            $selected = collect($quantities)->filter(fn($q) => is_numeric($q) && (float)$q > 0)->count();
        @endphp
        @if ($selected > 0)
        <p class="text-xs text-gray-500 text-center">
            {{ $selected }} {{ $selected === 1 ? 'produs selectat' : 'produse selectate' }}
        </p>
        @endif

        {{-- Buton TRIMITE --}}
        <button wire:click="submit"
                wire:loading.attr="disabled"
                class="w-full py-4 rounded-2xl bg-brand text-white font-bold text-base shadow
                       active:bg-red-800 disabled:opacity-60 transition-all touch-manipulation">
            <span wire:loading.remove>
                @if ($order)
                    Actualizează comanda
                @else
                    Trimite comanda
                @endif
            </span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                Se trimite…
            </span>
        </button>

    </div>

</div>

@endif
