<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaithPref extends Model
{
    //
    
    protected $table = 'faith_pref';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 
    public function ethnicity() {
        return $this->hasOne('Faith');
    }

}
