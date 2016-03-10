<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BxPhotosMain extends Model
{
    //
    
    protected $table = 'bx_photos_main';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
