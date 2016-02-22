<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BirthFatherPref extends Model
{
    //
    
    protected $table = 'Birthfather_pref';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

    public function birthFather() {
        return $this->hasOne('Birthfather');
    }

}
