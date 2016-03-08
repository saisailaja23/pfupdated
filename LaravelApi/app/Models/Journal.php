<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    //
    
    protected $table = 'bx_blogs_posts';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
