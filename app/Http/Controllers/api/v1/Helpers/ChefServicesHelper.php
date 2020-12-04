<?php


namespace App\Http\Controllers\api\v1\Helpers;


class ChefServicesHelper
{
    public static function formatServices($chefServices, $type='full'){
        $formattedServices = [];

        foreach ($chefServices as $chefService) {
            $options = $chefService->options;
            $totalOptions = $options->count();

            if ($type == 'full'){
                $formattedService = [
                    'id' => $chefService->id,
                    'title' => $chefService->service->name,
                    'totalOptions' => $totalOptions,
                    'created' => $chefService->created_at,
                    'status' => $chefService->status,
                    'options' => $options->makeHidden(['updated_at', 'chef_service_id'])
                ];
            } else {
                $formattedService = [
                    'id' => $chefService->id,
                    'title' => $chefService->service->name,
                    'totalOptions' => $totalOptions,
                    'status' => $chefService->status,
                ];
            }

            array_push($formattedServices, $formattedService);

        }

        return $formattedServices;
    }
}
