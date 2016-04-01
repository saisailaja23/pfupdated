<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    //
    
    protected $table = 'aqb_membership_vouchers_codes';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
