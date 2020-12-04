<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    /* Order Statuses Start */
    public static $PAID = 1;
    public static $PAY_ON_DELIVERY = 2;
    public static $DISPATCHED = 3;
    public static $CLEARED = 4;
    public static $CANCELLED = 5;
    /* Order Statuses End */

    public function options()
    {
        return $this->hasMany(OrderOption::class);
    }

    public function addOption($orderOption)
    {
        return $this->options()->create([
            'chef_service_option_id' => $orderOption['chef_service_option_id'],
            'qty' => $orderOption['qty'],
            'price' => $orderOption['price']
        ]);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function changeStatus($status)
    {
        return $this->update([
            'status' => $status
        ]);
    }


}
