<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'stock', 'prices','slug'
    ];

    protected $hidden = [
        
    ];

    // public function user(){
    //     return $this->hasOne( User::class, 'id', 'users_id');
    // }

    public function galleries() 
    {
        return $this->hasMany(ProductGallery::class, 'products_id', 'id');
    }
}