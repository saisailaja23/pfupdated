<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eprofile extends Model
{
    //
    
    protected $table = 'aqb_pc_members_blocks';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
