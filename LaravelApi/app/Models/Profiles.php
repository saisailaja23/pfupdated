<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class Profiles extends Model
{
    
    //protected $table='Profiles';
    public $timestamps = false;
    protected $dateFormat = 'U';

    public function user() {
        return $this->belongsTo('User');
    } 
}
