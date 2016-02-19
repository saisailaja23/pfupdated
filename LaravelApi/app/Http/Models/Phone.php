<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    //
    
    protected $table = 'Phone_number';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

}
