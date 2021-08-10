<?php

namespace App\Models;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Locations extends Model
{
    use HasFactory;
    protected $fillable = [
        'place',
        'name',
        'description',
        'note_average',
        'stars',
        'image',
        'state',
        'owner_name',
        'owner_phone',
        'category_id'

    ];

    protected $foreignKey = ['category_id'];
    // protected $appends = ['categoryname'];

    public  function category()
    {
        return  $this->belongsTo(Categories::class);
    }
    //     $cat = Categories::where('id', $this->attributes['category_id'])->get();
    //     return $cat;
    // }

    // public function getCategoryNameAttribute()
    // {
    //     $cat = $this->category();
    //     if (isset($cat[0])) {
    //         return $cat[0]->name;
    //     }
    //     return '';
    // }
}
