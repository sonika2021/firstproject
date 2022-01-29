<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\category;

class tblproduct extends Model
{
    protected $fillable = [
        'name', 'price'
    ];
    
    public function Categories()
    {
        return $this->belongsToMany(category::class);
        //return $this->hasMany('App\tblcategory','id') ;
    }
}
