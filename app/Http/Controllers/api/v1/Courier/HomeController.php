<?php

namespace App\Http\Controllers\api\v1\Courier;

use App\Http\Controllers\api\v1\Chef\ServiceController;
use App\Http\Controllers\api\v1\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Courier;
use App\Models\Order;
use App\Models\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $todayIncome = UserAccount::where('user_id', $user->id)
            ->where('type', 'c')
            ->whereDate('created_at', Carbon::today())
            ->get()->sum('amount');

        $unPaidAmount = $user->accountBalance();

        $courierInfo = Courier::where('user_id', $user->id)->first();
        $orders = Order::where('courier_id', $courierInfo->id)->where('status', 3)->get();

        $todayOrders = Order::where('courier_id', $courierInfo->id)
            ->where('status','>', 0)->where('status', '<>', 5)
            ->whereDate('created_at', Carbon::today())
            ->get()->count();


        return new DataResource([
            'name' => $courierInfo->hotel,
            'status' => $courierInfo->status,
            'city' => $user->city->name,
            'todayIncome' => $todayIncome,
            'todayOrders' => $todayOrders,
            'unpaidAmount' => $unPaidAmount,
            'orders' => OrderHelper::formatOrders($orders, 'chef', 'mini')
        ]);
    }

    public function update(Request $request)
    {
        $courierInfo = Courier::where('user_id', auth()->user()->id)->first();

        $courierInfo->update([
            'status' => $request->status
        ]);

        return response([
            'status' => 1,
            'message' => 'Status updated'
        ]);
    }
}
