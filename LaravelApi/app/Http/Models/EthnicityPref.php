<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class EthnicityPref extends Model
{
    //
    
    protected $table = 'Ethnicity_pref';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 
    public function ethnicity() {
        return $this->hasOne('Ethnicity');
    }

}
