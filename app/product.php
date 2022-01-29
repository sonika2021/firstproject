<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'name', 'price'
    ];
    
    public function Categories()
    {
        return $this->belongsToMany(category::class);        
    }
}
