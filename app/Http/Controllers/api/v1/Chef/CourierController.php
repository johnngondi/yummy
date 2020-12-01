<?php

namespace App\Http\Controllers\api\v1\Chef;

use App\Http\Controllers\api\v1\Helpers\CourierHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::where('status', 1)->get()->makeHidden(['created_at', 'updated_at', 'status', 'user_id']);

        return new DataResource([
            'couriers' => CourierHelper::formatCouriers($couriers)
        ]);
    }
}
