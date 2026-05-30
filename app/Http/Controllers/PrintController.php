<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Route as DeliveryRoute;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PrintController extends Controller
{
    public function singleOrder(Order $order): View
    {
        $order->load('store.route', 'user', 'items.product.category');

        return view('print.order', compact('order'));
    }

    public function bulkOrders(Request $request): View
    {
        $orders = $this->buildOrdersQuery($request)->get();

        return view('print.bulk', compact('orders'));
    }

    public function singleOrderPdf(Order $order): Response
    {
        $order->load('store.route', 'user', 'items.product.category');

        $pdf = Pdf::loadView('print.order', compact('order'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('comanda-'.$order->id.'.pdf');
    }

    public function bulkOrdersPdf(Request $request): Response
    {
        $orders = $this->buildOrdersQuery($request)->get();

        $pdf = Pdf::loadView('print.bulk', compact('orders'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('comenzi-'.date('Y-m-d').'.pdf');
    }

    private function buildOrdersQuery(Request $request)
    {
        $query = Order::with('store.route', 'user', 'items.product.category')
            ->orderBy('route_id')
            ->orderBy('store_id');

        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('data')) {
            $query->where('data', $request->data);
        } else {
            $query->where('data', today()->toDateString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ids')) {
            $ids = array_filter(array_map('intval', explode(',', $request->ids)));
            $query->whereIn('id', $ids);
        }

        return $query;
    }
}
