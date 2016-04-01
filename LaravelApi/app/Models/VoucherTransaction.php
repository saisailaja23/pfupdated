<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherTransaction extends Model
{
    //
    
    protected $table = 'aqb_membership_vouchers_transactions';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
