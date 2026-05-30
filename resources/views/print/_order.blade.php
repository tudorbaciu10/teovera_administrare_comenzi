{{--
    Partial comun pentru o singură comandă (un magazin).
    Folosit de: print/route.blade.php (browser) și print/route-pdf.blade.php (PDF).

    Variabile primite:
      $order  -> comanda (cu store, route, user, items.product.category încărcate)
      $lang   -> 'ro' sau 'ru'
      $index  -> numărul comenzii în listă (1, 2, 3...)
      $total  -> câte comenzi sunt în total

    Logica 2 coloane:
      Produsele sunt aplatizate într-un array plat ['cat' => ..., 'item' => ...],
      tăiate la ceil(n/2): primele n/2 → coloana stângă (sus→jos),
      restul → coloana dreaptă. Dacă o categorie e tăiată la mijloc,
      capul ei se repetă în coloana dreaptă.
      Pragul: < 4 produse → o singură coloană.
      Ajustare prag: schimbă constanta 4 din $useTwo de mai jos.
--}}
@php
    $grouped = $order->items
        ->sortBy(fn($i) => [
            $i->product->category->ordine_sortare,
            mb_strtolower($i->product->{'nume_' . $lang}),
        ])
        ->groupBy(fn($i) => $i->product->category->{'nume_' . $lang});

    // Aplatizare în array plat păstrând ordinea categorii + produse
    $flatRows = [];
    foreach ($grouped as $catName => $catItems) {
        foreach ($catItems as $item) {
            $flatRows[] = ['cat' => $catName, 'item' => $item];
        }
    }

    $totalItems = count($flatRows);

    // Prag: sub 4 produse → o singură coloană
    $useTwo = $totalItems >= 4;
    $half   = $useTwo ? (int) ceil($totalItems / 2) : 0;

    $leftRows  = $useTwo ? array_slice($flatRows, 0, $half) : $flatRows;
    $rightRows = $useTwo ? array_slice($flatRows, $half)    : [];

    $vanzatoare = $order->user?->name ?? '–';
@endphp

<div class="order-block">
    <div class="order-head-main">
        {{ __('print.magazin') }}: {{ $order->store->denumire }}
        &nbsp;|&nbsp; {{ __('print.ruta') }}: {{ $order->route->nume }}
        &nbsp;|&nbsp; {{ __('print.data') }}: {{ \Carbon\Carbon::parse($order->data)->format('d.m.Y') }}
    </div>
    <div class="order-head-sub">
        {{ __('print.comanda') }} #{{ $order->id }}
        &middot; {{ __('print.vanzatoare') }}: {{ $vanzatoare }}
        &middot; {{ $order->store->localitate }}{{ $order->store->adresa ? ', ' . $order->store->adresa : '' }}
        &middot; {{ __('print.status') }}: {{ __('print.status_' . $order->status) }}
        &middot; {{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}
        <span class="order-counter">({{ $index }}/{{ $total }})</span>
    </div>

    @if ($useTwo)
        {{-- Layout 2 coloane: tabel cu 2 celule (compatibil dompdf) --}}
        <table class="item-table-2col">
            <tbody>
                <tr>
                    {{-- Coloana stângă: primele ⌈n/2⌉ produse --}}
                    <td class="col-half col-half-left">
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th class="col-prod">{{ __('print.produs') }}</th>
                                    <th class="col-qty">{{ __('print.cantitate') }}</th>
                                    <th class="col-um">{{ __('print.um') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $prevCat = null; @endphp
                                @foreach ($leftRows as $row)
                                    @if ($row['cat'] !== $prevCat)
                                        <tr class="cat-row">
                                            <td colspan="3">{{ $row['cat'] }}</td>
                                        </tr>
                                        @php $prevCat = $row['cat']; @endphp
                                    @endif
                                    <tr class="item-row">
                                        <td class="col-prod">{{ $row['item']->product->{'nume_' . $lang} }}</td>
                                        <td class="col-qty">{{ rtrim(rtrim(number_format($row['item']->cantitate, 2, '.', ''), '0'), '.') }}</td>
                                        <td class="col-um">{{ $row['item']->product->unitate }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>

                    {{-- Coloana dreaptă: restul produselor --}}
                    <td class="col-half col-half-right">
                        @if (count($rightRows) > 0)
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th class="col-prod">{{ __('print.produs') }}</th>
                                    <th class="col-qty">{{ __('print.cantitate') }}</th>
                                    <th class="col-um">{{ __('print.um') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $prevCat = null; @endphp
                                @foreach ($rightRows as $row)
                                    @if ($row['cat'] !== $prevCat)
                                        <tr class="cat-row">
                                            <td colspan="3">{{ $row['cat'] }}</td>
                                        </tr>
                                        @php $prevCat = $row['cat']; @endphp
                                    @endif
                                    <tr class="item-row">
                                        <td class="col-prod">{{ $row['item']->product->{'nume_' . $lang} }}</td>
                                        <td class="col-qty">{{ rtrim(rtrim(number_format($row['item']->cantitate, 2, '.', ''), '0'), '.') }}</td>
                                        <td class="col-um">{{ $row['item']->product->unitate }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        {{-- O singură coloană (< 4 produse) --}}
        <table class="item-table">
            <thead>
                <tr>
                    <th class="col-prod">{{ __('print.produs') }}</th>
                    <th class="col-qty">{{ __('print.cantitate') }}</th>
                    <th class="col-um">{{ __('print.um') }}</th>
                </tr>
            </thead>
            <tbody>
                @php $prevCat = null; @endphp
                @foreach ($leftRows as $row)
                    @if ($row['cat'] !== $prevCat)
                        <tr class="cat-row">
                            <td colspan="3">{{ $row['cat'] }}</td>
                        </tr>
                        @php $prevCat = $row['cat']; @endphp
                    @endif
                    <tr class="item-row">
                        <td class="col-prod">{{ $row['item']->product->{'nume_' . $lang} }}</td>
                        <td class="col-qty">{{ rtrim(rtrim(number_format($row['item']->cantitate, 2, '.', ''), '0'), '.') }}</td>
                        <td class="col-um">{{ $row['item']->product->unitate }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($order->observatii)
        <div class="order-note"><strong>{{ __('print.observatii') }}:</strong> {{ $order->observatii }}</div>
    @endif
</div>
