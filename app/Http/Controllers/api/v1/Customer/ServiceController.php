<?php

namespace App\Http\Controllers\api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function show(Service $service)
    {
        $chefService = $service->chefServices->first();

        return new DataResource([
            'name' => $service->name,
            'options' => $chefService->options->makeHidden(['created_at', 'updated_at', 'chef_service_id'])
        ]);
    }
}
