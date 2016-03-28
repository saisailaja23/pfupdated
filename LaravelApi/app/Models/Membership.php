<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    //
    
    protected $table = 'sys_acl_levels';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
