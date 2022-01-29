<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $fillable = [
        'title', 'parent_id'
    ];
    
    public function childs() {
        return $this->hasMany('App\category','parent_id','id') ;
    }

    public function Products()
    {
        return $this->belongsToMany(Product::class);
    }
}
