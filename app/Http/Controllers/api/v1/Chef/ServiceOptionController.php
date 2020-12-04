<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\ChefService;
use App\Models\ChefServiceOption;
use Illuminate\Http\Request;

class ServiceOptionController extends Controller
{

    public function store(Request $request, $service)
    {
        $option = ChefServiceOption::create([
            'chef_service_id' => $service,
            'title' => $request->title,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return new DataResource([
            'status' => 1,
            'message' => 'Service Option Created',
            'option' => $option->makeHidden(['chef_service_id', 'created_at'])
        ]);
    }

    public function show(ChefServiceOption $option)
    {
        return new DataResource([
            'option' => $option->makeHidden(['chef_service_id', 'created_at'])
        ]);
    }


    public function update(Request $request, ChefServiceOption $option)
    {
        $option->update([
            'title' => $request->title,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return response([
            'message' => 'Option Updated'
        ]);
    }

    public function changeStatus(ChefServiceOption $option, Request $request)
    {
        if ($request->status == 1){
            //update chef & service
            $option->chefService->update([
                'status' => 1
            ]);

            $option->chefService->chef->update([
                'status' => 1
            ]);
        }

        $option->update([
            'status' => $request->status
        ]);

        return response([
            'status' => 1,
            'message' => 'Option Status Updated'
        ]);
    }


    public function destroy(ChefServiceOption $option)
    {
        try {
            $option->delete();

            return response([
                'status' => 1,
                'message' => 'Option Deleted'
            ]);

        } catch (\Exception $e) {
            return response([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }

    }
}
