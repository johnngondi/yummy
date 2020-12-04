<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Courier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function info()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
