<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Route as DeliveryRoute;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.vanzatoare')]
class StoreOrder extends Component
{
    public Store $store;

    public array  $quantities  = [];
    public string $observatii  = '';
    public ?int   $userId      = null;

    public bool    $submitted = false;
    public ?Order  $order     = null;
    public ?string $errorMsg  = null;

    public function mount(string $token): void
    {
        $this->store = Store::where('token_acces', $token)
            ->with('route', 'users')
            ->firstOrFail();

        // Initializăm toate cantitățile la '' (gol = nu se comandă)
        $products = Category::with(['products' => fn ($q) => $q->where('activ', true)->orderBy('nume')])
            ->orderBy('ordine_sortare')
            ->get()
            ->flatMap(fn ($cat) => $cat->products);

        foreach ($products as $product) {
            $this->quantities[(string) $product->id] = '';
        }

        // Dacă există deja o comandă pentru azi, o preîncărcăm
        $existing = Order::where('store_id', $this->store->id)
            ->where('data', today()->toDateString())
            ->with('items')
            ->first();

        if ($existing) {
            $this->order     = $existing;
            $this->submitted = true;
            $this->observatii = $existing->observatii ?? '';
            $this->userId    = $existing->user_id;

            foreach ($existing->items as $item) {
                $this->quantities[(string) $item->product_id] = rtrim(rtrim((string) $item->cantitate, '0'), '.');
            }
        }
    }

    // ─── Increment / Decrement cantitate ────────────────────────────────────

    public function incrementQty(string $productId, float $step): void
    {
        $current = (float) ($this->quantities[$productId] ?? 0);
        $this->quantities[$productId] = round($current + $step, 3);
    }

    public function decrementQty(string $productId, float $step): void
    {
        $current = (float) ($this->quantities[$productId] ?? 0);
        $new = round($current - $step, 3);
        $this->quantities[$productId] = $new > 0 ? $new : '';
    }

    // ─── Calculăm dacă suntem după cut-off ───────────────────────────────────

    public function isCutoffPassed(): bool
    {
        $route = $this->store->route;
        if (! $route || ! $route->zi_cutoff || ! $route->ora_cutoff) {
            return false;
        }

        $dayMap = [
            'luni'     => 1,
            'marti'    => 2,
            'miercuri' => 3,
            'joi'      => 4,
            'vineri'   => 5,
            'sambata'  => 6,
            'duminica' => 7,
        ];

        $cutoffDayNum = $dayMap[$route->zi_cutoff] ?? null;
        if ($cutoffDayNum === null) {
            return false;
        }

        $todayNum = (int) now()->format('N'); // 1=luni … 7=duminică

        if ($todayNum === $cutoffDayNum) {
            return now()->format('H:i') >= substr($route->ora_cutoff, 0, 5);
        }

        return false;
    }

    // ─── Submitere ────────────────────────────────────────────────────────────

    public function submit(): void
    {
        $this->errorMsg = null;

        if ($this->isCutoffPassed()) {
            $this->errorMsg = 'Termenul de trimitere a comenzii a expirat.';
            return;
        }

        if (! $this->userId) {
            $this->errorMsg = 'Selectează persoana care trimite comanda.';
            return;
        }

        $items = collect($this->quantities)
            ->filter(fn ($q) => is_numeric($q) && (float) $q > 0)
            ->map(fn ($q, $id) => ['product_id' => (int) $id, 'cantitate' => (float) $q]);

        if ($items->isEmpty()) {
            $this->errorMsg = 'Adaugă cel puțin un produs în comandă.';
            return;
        }

        DB::transaction(function () use ($items) {
            // Dacă există deja o comandă pentru azi, o actualizăm
            if ($this->order) {
                $this->order->items()->delete();
                $this->order->update([
                    'user_id'    => $this->userId,
                    'observatii' => $this->observatii ?: null,
                    'status'     => 'trimisa',
                ]);
            } else {
                $this->order = Order::create([
                    'store_id'   => $this->store->id,
                    'user_id'    => $this->userId,
                    'data'       => today()->toDateString(),
                    'route_id'   => $this->store->route_id,
                    'status'     => 'trimisa',
                    'observatii' => $this->observatii ?: null,
                ]);
            }

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $this->order->id,
                    'product_id' => $item['product_id'],
                    'cantitate'  => $item['cantitate'],
                ]);
            }
        });

        $this->submitted = true;
        $this->order->load('items.product.category');
    }

    // ─── Editare ──────────────────────────────────────────────────────────────

    public function editOrder(): void
    {
        if ($this->isCutoffPassed()) {
            return;
        }
        $this->submitted = false;
    }

    // ─── Render ───────────────────────────────────────────────────────────────

    public function render(): View
    {
        $categories = Category::with([
            'products' => fn ($q) => $q->where('activ', true)->orderBy('nume'),
        ])
            ->orderBy('ordine_sortare')
            ->get();

        $vanzatoare = $this->store->users()
            ->where('rol', 'vanzatoare')
            ->orderBy('name')
            ->get();

        return view('livewire.store-order', [
            'categories'    => $categories,
            'vanzatoare'    => $vanzatoare,
            'cutoffPassed'  => $this->isCutoffPassed(),
            'storeTitle'    => $this->store->denumire,
        ])->title($this->store->denumire . ' — Comandă');
    }
}
