<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category_product extends Model
{
    protected $table = "category_product";
    
    protected $fillable = [
        'product_id', 'category_id'
    ];
}
