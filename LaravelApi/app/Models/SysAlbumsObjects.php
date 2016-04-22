<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysAlbumsObjects extends Model
{
    //
    
    protected $table = 'sys_albums_objects';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
