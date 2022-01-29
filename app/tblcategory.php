<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\tblproduct;

class tblcategory extends Model
{       
    protected $fillable = [
        'title', 'parent_id'
    ];
    
    public function childs() {
        return $this->hasMany('App\tblcategory','parent_id','id') ;
    }

    public function Products()
    {
        return $this->belongsToMany(Product::class,'tblproduct_categories','product_id');
    }
}
