<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    //
    
    protected $table = 'sys_acl_levels_members';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
