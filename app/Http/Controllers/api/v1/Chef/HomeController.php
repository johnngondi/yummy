<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\api\v1\Helpers\OrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Chef;
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

        $chefInfo = Chef::where('user_id', $user->id)->first();
        $orders = Order::where('chef_id', $chefInfo->id)->where('status', 1)->orWhere('status', 2)->get();

        $todayOrders = Order::where('chef_id', $chefInfo->id)
            ->where('status','>', 0)->where('status', '<>', 5)
            ->whereDate('created_at', Carbon::today())
            ->get()->count();


        return new DataResource([
            'name' => $chefInfo->hotel,
            'status' => $chefInfo->status,
            'city' => $user->city->name,
            'todayIncome' => $todayIncome,
            'todayOrders' => $todayOrders,
            'unpaidAmount' => $unPaidAmount,
            'orders' => OrderHelper::formatOrders($orders, 'chef', 'mini')
        ]);
    }

    public function update(Request $request)
    {
        $chefInfo = Chef::where('user_id', auth()->user()->id)->first();

        $serviceController = new ServiceController();
        $chefInfo->services->map(function ($service) use ($serviceController, $request) {
            $serviceController->update($request, $service);
        });

        $chefInfo->update([
            'status' => $request->status
        ]);

        return response([
            'status' => 1,
            'message' => 'Status and all services & options status updated'
        ]);
    }
}
