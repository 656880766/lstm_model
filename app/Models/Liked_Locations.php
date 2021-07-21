<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liked_Locations extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'location_id'
    ];
}
