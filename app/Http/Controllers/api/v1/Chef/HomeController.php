<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\api\v1\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Chef;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $chefInfo = Chef::where('user_id', $user->id)->first();

        $orders = Order::where('chef_id', $chefInfo->id)->where('status', 1)->orWhere('status', 2)->get();

        return new DataResource([
            'name' => $chefInfo->hotel,
            'status' => $chefInfo->status,
            'city' => $user->city->name,
            'orders' => OrderHelper::formatOrders($orders, 'chef', 'mini')
        ]);
    }
}
