<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    protected $fillable = [
        'place',
        'name',
        'description',
        'note_average',
        'owner_name',
        'owner_phone',
        'category_id'

    ];


    public function category()
    {
        return $this->BelongsTo('Category');
    }
}
