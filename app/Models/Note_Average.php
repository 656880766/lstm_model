<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note_Average extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'location_id',
        'note'
    ];
    protected $table = 'note_average';
}
