<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profiletype extends Model
{
    //
    
    protected $table = 'aqb_pts_profile_types';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
