<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserve extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'location_id',
        'start_day',
        'finish_day'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
    public function locations()
    {
        $this->belongsTo(Locations::class);
    }
}
