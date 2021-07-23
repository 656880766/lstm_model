<?php

namespace App\Models;

use App\Models\Locations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'blanket',
        'description'

    ];



    public function locations()
    {
        return $this->hasMany(Locations::class);
    }
}
