<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Birthfather extends Model
{
    //
    
   
    public $timestamps = false;    
  	protected $table = 'Birthfather_status';

    public function birthFatherPref() {
        return $this->belongsTo('BirthFatherPref');
    } 

}
