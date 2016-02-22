<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilesDraft extends Model
{
    //

    protected $table = 'profiles_draft';
   
    public $timestamps = false;
    protected $dateFormat = 'U';

    public function user() {
        return $this->belongsTo('User');
    } 
}
