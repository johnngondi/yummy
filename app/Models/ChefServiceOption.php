<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChefServiceOption extends Model
{
    use HasFactory;

    public function chefService()
    {
        return $this->belongsTo(ChefService::class);
    }
}
