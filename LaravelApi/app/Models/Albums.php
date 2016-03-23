<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Albums extends Model
{
    //
    
    protected $table = 'sys_albums';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
