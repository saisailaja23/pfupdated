<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    //
   
    public $timestamps = false;
    protected $dateFormat = 'U';

    public function user() {
        return $this->belongsTo('User');
    } 
}
