<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\api\v1\Helpers\ChefServicesHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Chef;
use App\Models\ChefService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $chefInfo = Chef::where('user_id', $user->id)->first();

        return new DataResource([
            'services' => ChefServicesHelper::formatServices($chefInfo->services, 'mini')
        ]);
    }



    public function show(ChefService $service)
    {

        return new DataResource([
            'service' => ChefServicesHelper::formatServices([$service])[0]
        ]);
    }


    public function update(Request $request, ChefService $service)
    {
        $status = $request->status;
        $service->options->map(function($option) use ($status) {
            $option->update([
                'status' => $status
            ]);
        });

        if ($status === 1){
            //enable chef
            $service->chef->update([
                'status' => 1
            ]);
        }


        $service->update([
            'status' => $request->status
        ]);

        return response([
            'status' => 1,
            'message' => 'Service an all of its options Status Updated'
        ]);
    }


}
