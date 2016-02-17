<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class SocialSite extends Model
{
    //
    
   protected $table = 'Acc_social_site';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('User');
    } 

}
