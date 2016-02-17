<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    //
    
   
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

    public function ethnicityPref()
    {
        return $this->hasMany('AlbumPhoto');
    }

}
