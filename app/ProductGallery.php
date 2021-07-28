<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    protected $table = "product_galleries";
    protected $primaryKey = 'id_gallery';

    protected $fillable = [
        'photos', 'products_id'
    ];

    protected $hidden = [
        
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class, 'products_id', 'id_product');
    }
}
