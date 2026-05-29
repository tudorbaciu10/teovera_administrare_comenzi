<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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
        $ids = array_filter(array_map('intval', explode(',', $request->query('ids', ''))));
        $orders = Order::whereIn('id', $ids)
            ->with('store.route', 'user', 'items.product.category')
            ->orderBy('store_id')
            ->get();

        return view('print.bulk', compact('orders'));
    }
}
