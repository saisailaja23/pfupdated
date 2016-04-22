<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    //
    protected $table = 'letter';
   
    public $timestamps = false;
    protected $dateFormat = 'U';

    public function user() {
        return $this->belongsTo('User');
    } 
}
