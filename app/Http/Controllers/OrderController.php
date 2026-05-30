<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Route as DeliveryRoute;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $routes = DeliveryRoute::orderBy('nume')->get();

        $query = Order::with(['store', 'route', 'user', 'items.product.category'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('data')) {
            $query->where('data', $request->data);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('orders.index', compact('orders', 'routes'));
    }

    public function show(Order $order): View
    {
        $order->load(['store.route', 'user', 'items.product.category']);

        return view('orders.show', compact('order'));
    }

    public function markPrintata(Order $order): RedirectResponse
    {
        $order->update(['status' => 'printata']);

        return back()->with('success', __('admin.salvat_succes'));
    }

    public function markLivrata(Order $order): RedirectResponse
    {
        $order->update(['status' => 'livrata']);

        return back()->with('success', __('admin.salvat_succes'));
    }

    public function markTrimisa(Order $order): RedirectResponse
    {
        $order->update(['status' => 'trimisa']);

        return back()->with('success', __('admin.salvat_succes'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->items()->delete();
        $order->delete();

        return redirect()->route('orders.index')->with('success', __('admin.sters_succes'));
    }

    public function poll(): JsonResponse
    {
        $count = Order::where('status', 'trimisa')
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        return response()->json(['count' => $count]);
    }

    public function exportPdf(Request $request): Response
    {
        $orders = Order::with(['store', 'route', 'user', 'items.product.category'])
            ->orderBy('data', 'desc')
            ->orderBy('route_id')
            ->when($request->filled('route_id'), fn ($q) => $q->where('route_id', $request->route_id))
            ->when($request->filled('data'),     fn ($q) => $q->where('data', $request->data))
            ->when($request->filled('status'),   fn ($q) => $q->where('status', $request->status))
            ->get();

        $pdf = Pdf::loadView('print.bulk', compact('orders'))->setPaper('a4', 'portrait');

        return $pdf->download('comenzi-'.date('Y-m-d').'.pdf');
    }

    public function exportCsv(Request $request): Response
    {
        $query = Order::with(['store', 'route', 'user', 'items.product'])
            ->orderBy('data', 'desc')
            ->orderBy('route_id');

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }
        if ($request->filled('data')) {
            $query->where('data', $request->data);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $rows = [];
        $rows[] = ['ID', 'Magazin', 'Localitate', 'Ruta', 'Data', 'Status', 'Vanzatoare', 'Produs', 'Cantitate', 'Unitate', 'Observatii'];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $rows[] = [
                    $order->id,
                    $order->store?->denumire,
                    $order->store?->localitate,
                    $order->route?->nume,
                    $order->data?->format('d.m.Y'),
                    $order->status,
                    $order->user?->name,
                    $item->product?->nume_ro,
                    number_format($item->cantitate, 2, '.', ''),
                    $item->product?->unitate,
                    $order->observatii,
                ];
            }
        }

        $csv = '';
        // UTF-8 BOM for Excel
        $csv .= "\xEF\xBB\xBF";
        foreach ($rows as $row) {
            $csv .= implode(';', array_map(fn ($v) => '"'.str_replace('"', '""', (string) $v).'"', $row))."\r\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="comenzi_'.date('Y-m-d').'.csv"',
        ]);
    }
}
