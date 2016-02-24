<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Models;

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
