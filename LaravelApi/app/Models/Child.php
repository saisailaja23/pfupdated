<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    //
    
   
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

}
