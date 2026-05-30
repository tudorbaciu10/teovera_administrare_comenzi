<div>

{{-- ── Header sticky ── --}}
<header class="comanda-header">
    <div class="comanda-header-top">
        <span class="comanda-brand">Avicola-Teovera</span>
        <div class="lang-toggle">
            <a href="{{ route('lang.switch', 'ro') }}"
               class="{{ app()->getLocale() === 'ro' ? 'active' : '' }}">RO</a>
            <a href="{{ route('lang.switch', 'ru') }}"
               class="{{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
        </div>
    </div>
    <div class="comanda-store">{{ $storeTitle }}</div>
    <div class="comanda-meta">
        {{ $store->route?->nume }} &mdash; {{ now()->format('d.m.Y') }}
    </div>
</header>

<div class="comanda-body">

@if($submitted && $order)
{{-- ══ ECRAN CONFIRMARE ═══════════════════════════════════════════════ --}}

<div class="confirmare-card">
    <div class="confirmare-icon">&#10003;</div>
    <div class="confirmare-titlu">{{ __('comanda.confirmare_titlu') }}</div>
    <div class="confirmare-text">{{ __('comanda.confirmare_text') }}</div>

    <div class="confirmare-produse">
        @php
            $locale = app()->getLocale();
            $byCategory = $order->items->sortBy([
                fn ($a, $b) => $a->product->category->ordine_sortare <=> $b->product->category->ordine_sortare,
                fn ($a, $b) => $a->product->{'nume_'.$locale} <=> $b->product->{'nume_'.$locale},
            ])->groupBy(fn ($item) => $item->product->category->ordine_sortare.'|'.$item->product->category->{'nume_'.$locale});
        @endphp

        @foreach($byCategory as $catKey => $items)
            <div class="confirmare-cat">{{ explode('|', $catKey)[1] ?? $catKey }}</div>
            @foreach($items as $item)
            <div class="confirmare-item">
                <span>{{ $item->product->{'nume_'.$locale} }}</span>
                <strong>{{ rtrim(rtrim(number_format($item->cantitate, 3, '.', ''), '0'), '.') }} {{ __('comanda.'.$item->product->unitate) }}</strong>
            </div>
            @endforeach
        @endforeach
    </div>

    @if(!$cutoffPassed)
    <button wire:click="editOrder" class="btn-edit">
        {{ __('comanda.btn_editeaza') }}
    </button>
    @endif
</div>

@elseif($cutoffPassed && !$order)
{{-- ══ CUTOFF BLOCAT (fără comandă existentă) ═══════════════════════ --}}

<div class="cutoff-card">
    <div class="cutoff-icon">&#9200;</div>
    <div class="cutoff-titlu">{{ __('comanda.cutoff_depasit') }}</div>
    <div class="cutoff-text">
        @if($store->route?->zi_cutoff && $store->route?->ora_cutoff)
            {{ __('comanda.cutoff_mesaj', [
                'zi'  => ucfirst($store->route->zi_cutoff),
                'ora' => substr($store->route->ora_cutoff, 0, 5),
            ]) }}
        @endif
    </div>
</div>

@else
{{-- ══ FORMULAR COMANDĂ ════════════════════════════════════════════════ --}}

@if($errorMsg)
<div class="error-msg">{{ $errorMsg }}</div>
@endif

<form wire:submit.prevent="submit">

    {{-- Produse grupate pe categorie --}}
    @foreach($categories as $category)
    @php $locale = app()->getLocale(); @endphp
    <div class="cat-section">
        <div class="cat-title">{{ $category->{'nume_'.$locale} }}</div>

        @foreach($category->products as $product)
        @php $qty = $quantities[(string)$product->id] ?? ''; @endphp
        <div class="product-row {{ is_numeric($qty) && (float)$qty > 0 ? 'has-qty' : '' }}">
            <div style="flex:1">
                <div class="product-name">{{ $product->{'nume_'.$locale} }}</div>
                <div class="product-unit">{{ __('comanda.'.$product->unitate) }}</div>
            </div>
            <div class="qty-wrap">
                <button type="button"
                        class="qty-btn"
                        wire:click="decrementQty('{{ $product->id }}', {{ $product->unitate === 'kg' ? 0.5 : 1 }})">&#8722;</button>
                <input type="number"
                       class="qty-input"
                       wire:model.lazy="quantities.{{ $product->id }}"
                       step="{{ $product->unitate === 'kg' ? '0.5' : '1' }}"
                       min="0"
                       placeholder="0">
                <button type="button"
                        class="qty-btn"
                        wire:click="incrementQty('{{ $product->id }}', {{ $product->unitate === 'kg' ? 0.5 : 1 }})">+</button>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach

    {{-- Rezumat produse selectate --}}
    <div class="selected-summary">
        {{ __('comanda.produse_selectate', ['n' => '<strong id="selectedCount">0</strong>']) }}
    </div>

    {{-- Vânzătoare --}}
    <div class="comanda-section">
        <label>{{ __('comanda.selecteaza_vanzator') }}</label>
        <select wire:model="userId">
            <option value="">&#8212; {{ __('comanda.selecteaza_vanzator') }} &#8212;</option>
            @foreach($vanzatoare as $v)
                <option value="{{ $v->id }}">{{ $v->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Observații --}}
    <div class="comanda-section">
        <label>{{ __('comanda.observatii') }}</label>
        <textarea wire:model="observatii"
                  rows="3"
                  placeholder="{{ __('comanda.observatii_ph') }}"></textarea>
    </div>

    {{-- Submit --}}
    <button type="submit" class="btn-submit" wire:loading.attr="disabled">
        <span wire:loading.remove>{{ __('comanda.btn_trimite') }}</span>
        <span wire:loading>&#8230;</span>
    </button>

</form>

@endif

</div>{{-- /comanda-body --}}

</div>
