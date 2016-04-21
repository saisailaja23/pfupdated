<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
    //
    
    protected $table = 'sys_email_templates';
    public $timestamps = false;
    
    public function user() {
        return $this->belongsTo('user');
    } 

}
