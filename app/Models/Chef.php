<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Chef extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function info()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class)->latest();
    }

    public function services()
    {
       return $this->hasMany(ChefService::class, 'chef_id');
    }
}
