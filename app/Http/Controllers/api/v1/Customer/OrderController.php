<?php

namespace App\Http\Controllers\api\v1\Customer;

use App\Http\Controllers\api\v1\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\ChefServiceOption;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $allOrders = auth()->user()->orders;
        $orders = OrderHelper::formatOrders($allOrders, 'customer', 'mini');

        return new DataResource([
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $orderRequestItems = json_decode($request->getContent());
        $user = auth()->user();
        $chefService = ChefServiceOption::find($orderRequestItems[0]->id)->chefService;
        $order_origin = $chefService->chef->gps_coords;
        $serviceFee = $chefService->service->service_fee_per_unit;

        //create order
        $order = Order::create([
            'user_id' => $user->id,
            'service_id' => $chefService->service->id,
            'chef_id' => $chefService->chef->id,
            'order_origin' => $order_origin
        ]);

        $sub_total = 0;
        $discount = $user->coupons->where('status', 0)->sum('amount');

        foreach ($orderRequestItems as $orderRequestItem){
            $chef_service_option_id = $orderRequestItem->id;
            $qty = $orderRequestItem->qty;
            $price = ChefServiceOption::find($orderRequestItem->id)->price + $serviceFee;

            $sub_total += $qty * $price;

            //add it
            $order->addOption([
                'chef_service_option_id' => $chef_service_option_id,
                'qty' => $qty,
                'price' => $price
            ]);
        }

        $order->sub_total = $sub_total;
        $order->discount = $discount;
        $order->total = $sub_total - $discount;
        $order->save();


        return new DataResource([
            'order' => [
                'id' => $order->id
            ],
            'message' => 'Order Created Successfully!'
        ]);
    }

    public function show(Order $order)
    {
        return new DataResource([
            'order' => OrderHelper::formatOrders([$order], 'customer')[0]
        ]);
    }

    public function orderAddress(Order $order)
    {
        return new DataResource([
            'order' => [
                'id' => $order->id,
                'qty' => $order->options->sum('qty'),
                'address' => $order->user_address_id,
                'total' => $order->total,
                'shipping_fee' => $order->service->shipping_fee_per_km,
                'shipping_limit' => $order->service->shipping_limit,
            ],
            'addresses' => auth()->user()->addresses->makeHidden(['user_id', 'created_at', 'updated_at'])
        ]);
    }

    public function setShippingInfo(Order $order, Request $request)
    {
        $shippingData = json_decode($request->getContent());

        $order->user_address_id = $shippingData->address;
        $order->total = $order->sub_total - $order->discount + $shippingData->fee;
        $order->shipping_fee = $shippingData->fee;
        $order->order_destination = UserAddress::find($shippingData->address)->gps_coords;

        $order->update();

        return new DataResource([
            'order' => [
                'id' => $order->id
            ],
            'message' => 'Shipping info updated Successfully!'
        ]);
    }

    public function changeStatus(Order $order, Request $request)
    {
        $statusData = json_decode($request->getContent());
        $order->changeStatus($statusData->status);

        return new DataResource([
            'order' => [
                'id' => $order->id,
                'status' => $order->status
            ],
            'message' => 'Status updated Successfully!'
        ]);
    }
}
