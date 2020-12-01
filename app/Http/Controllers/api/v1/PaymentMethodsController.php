<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Order;
use App\Models\PaymentMode;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    public function index()
    {
        return new DataResource([
            'methods' => PaymentMode::where('status', 1)->get()->makeHidden(['created_at', 'updated_at'])
        ]);
    }

    public function store(Request $request, Order $order)
    {

        $order->update([
            'payment_mode_id' => json_decode($request->getContent())->method
        ]);

        return new DataResource([
            'order' => [
                'id' => $order->id
            ],
            'message' => 'Payment Method Set.'
        ]);
    }
}
