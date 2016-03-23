<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    
   protected $table = 'RayVideoFiles';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
