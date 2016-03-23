<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialNeed extends Model
{
    //
    
    protected $table = 'special_need';
    public $timestamps = false;
    
    public function specialNeedPref() {
        return $this->belongsTo('SpecialNeedPref');
    } 

}
