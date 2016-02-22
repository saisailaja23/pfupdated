<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialNeedPref extends Model
{
    //
    
    protected $table = 'Special_need_pref';
    public $timestamps = false;
    
    public function specialNeed() {
        return $this->hasOne('SpecialNeed');
    }



}
