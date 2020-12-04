<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chef()
    {
        return $this->belongsTo(Chef::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function options()
    {
        return $this->hasMany(ChefServiceOption::class);
    }
}
