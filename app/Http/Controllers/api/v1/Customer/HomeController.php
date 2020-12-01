<?php

namespace App\Http\Controllers\api\v1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\DataResource;
use App\Models\ChefService;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $services = [];
    public function index()
    {
      $user = auth()->user();
      $coupons = $user->coupons->where('status', 0)->sum('amount');
      $pendingOrders = $user->orders->where('status', '<>', 5)->count();

      $appRating = Review::all()->avg('rating');
      $recentReview = ($coupons == 0) ? Review::latest()->limit(1)->get()[0] : null;

      $allServices = Service::where('status', 1)->get();

      $allServices->map(function ($service) {
          $item = [
              'id' => $service->id,
              'name' => $service->name,
              'chefs' => ChefService::where('service_id', $service->id)->where('status', 1)->count()
          ];

          array_push($this->services, $item);
      });


      return new DataResource([
          'user' => [
              'first_name' => $user->first_name,
          ],
          'services' => empty($this->services) ? null : $this->services,
          'pendingOrders' => $pendingOrders,
          'coupons' => $coupons,
          'appRating' => (float)number_format($appRating,1),
          'recentReview' => $recentReview
      ]);
    }
}
