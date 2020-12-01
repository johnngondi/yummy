<?php

namespace App\Http\Controllers\api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function index()
    {
        return new DataResource([
            'addresses' => auth()->user()->addresses->makeHidden(['user_id', 'updated_at'])
        ]);
    }


    public function store(Request $request)
    {
        $addressInfo = json_decode($request->getContent());
        $address = UserAddress::create([
            'user_id' => auth()->user()->id,
            'title' => $addressInfo->title,
            'desc' => $addressInfo->desc,
            'gps_coords' => $addressInfo->coords
        ]);

        return new DataResource([
            'address' => $address->makeHidden(['user_id', 'updated_at']),
            'message' => 'Address Added Successfully'
        ]);

    }


    public function show(UserAddress $userAddress)
    {
        return new DataResource([
            'address' => $userAddress->makeHidden(['user_id', 'updated_at']),
        ]);
    }


    public function update(Request $request, UserAddress $userAddress)
    {
        //
    }


    public function destroy(UserAddress $userAddress)
    {
        if ($userAddress->user_id === auth()->user()->id) {
            $userAddress->delete();

            return response([
                'message' => 'Address Deleted'
            ]);
        } else {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }


    }
}
