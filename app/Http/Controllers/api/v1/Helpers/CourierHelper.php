<?php


namespace App\Http\Controllers\api\v1\Helpers;


class CourierHelper
{
    public static function formatCouriers($couriers)
    {
        $formattedCouriers = [];

        foreach ($couriers as $courier){

            $formattedCourier = [
                'id' => $courier->id,
                'name' => $courier->info->first_name . ' ' . $courier->info->last_name . ' (' . ucwords($courier->nickname) . ')',
                'photo' => $courier->info->profile_photo_path,
                'joined' => $courier->created_at->diffForHumans(),
                'status' => $courier->status,
            ];

            array_push($formattedCouriers, $formattedCourier);
        }

        return $formattedCouriers;
    }
}
