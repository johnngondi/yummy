<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\api\v1\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Chef;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $chefInfo = Chef::where('user_id', $user->id)->first();
        $allOrdersData = Order::where('status', '<>', 0)->where('status', '<>', 5)->latest()->paginate(10);
        $allOrders = $allOrdersData->items();

//        return $allOrdersData->nextPageUrl();

        $orders = OrderHelper::formatOrders($allOrders, 'chef', 'mini');

        return new DataResource([
            'orders' => $orders,
            'next' => $allOrdersData->nextPageUrl()
        ]);
    }

    public function show(Order $order)
    {
        return new DataResource([
            'order' => OrderHelper::formatOrders([$order], 'chef')
        ]);
    }

    public function dispatchOrder(Order $order, Request $request)
    {
        $order->update([
            'courier_id' => $request->courier,
            'status' => Order::$DISPATCHED
        ]);

        // Dispatch event to say order was dispatched and to who

        return response([
            'status' => 1,
            'message' => 'Order Dispatched'
        ]);
    }
}
