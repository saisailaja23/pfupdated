<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
    
    protected $table = 'bx_groups_main';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
