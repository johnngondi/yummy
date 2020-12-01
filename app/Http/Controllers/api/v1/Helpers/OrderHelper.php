<?php


namespace App\Http\Controllers\api\v1\Helpers;

class OrderHelper
{
    public static function formatOrders($orders, $role, $type = 'full')
    {
        $formattedOrders = [];

        foreach ($orders as $order){
            $options = [];
            $orderOptions = $order->options;
            foreach ($orderOptions as $orderOption){
                $option = [
                    'title' => $orderOption->chefServiceOption->title,
                    'qty' => $orderOption->qty
                ];

                array_push($options, $option);
            }

            if ($role != 'customer'){
                $customer = [
                    'id' => $order->user->id,
                    'name' => $order->user->first_name . ' ' . $order->user->last_name,
                    'photo' => $order->user->profile_photo_path
                ];
            }

            if ($role != 'chef'){
                $chef = [
                    'id' => $order->chef->id,
                    'hotel' => $order->chef->hotel,
                    'photo' => $order->chef->info->profile_photo_path
                ];
            }

            if ($order->courier_id !== null && $role !== 'courier'){
                $courier = [
                    'id' => $order->courier->info->id,
                    'name' => $order->courier->info->first_name . ' ' . $order->courier->info->last_name,
                    'photo' => $order->courier->info->profile_photo_path
                ];
            }

            if ($type == 'full') {
                $formattedOrder = [
                    'id' => $order->id,
                    'title' => $orderOptions->sum('qty'). ' '. $order->service->name,
                    'img' => $order->service->img,
                    'sub_total' => $order->sub_total,
                    'discount' => (float)$order->discount,
                    'shipping' => (float)$order->shipping_fee,
                    'total' => $order->total,
                    'created' => $order->created_at->diffForHumans(),
                    'status' => $order->status,
                    'options' => $options,
                    'customer' => (isset($customer)) ? $customer : null,
                    'chef' => (isset($chef)) ? $chef : null,
                    'courier' => (isset($courier)) ? $courier : null,

                ];
            } else {
                $formattedOrder = [
                    'id' => $order->id,
                    'title' => $orderOptions->sum('qty'). ' '. $order->service->name,
                    'img' => $order->service->img,
                    'total' => $order->total,
                    'created' => $order->created_at->diffForHumans(),
                    'status' => $order->status,
                ];
            }



            array_push($formattedOrders, $formattedOrder);

        }

        return $formattedOrders;
    }
}
