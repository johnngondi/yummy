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

        $orders = OrderHelper::formatOrders($chefInfo->orders, 'chef');

        return new DataResource([
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        return new DataResource([
            'order' => OrderHelper::formatOrders([$order], 'chef')
        ]);
    }
}
