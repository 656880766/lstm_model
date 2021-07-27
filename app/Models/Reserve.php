<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserve extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'location_id'
    ];

    public function customer()
    {
        $this->belongsTo(Customers::class);
    }
}
