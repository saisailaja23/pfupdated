<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Account extends Model
{
    //
      protected $table = 'Account';
      public $timestamps = false;

  

    public function role()
    {
        return $this->hasOne('Role');
    }

    public function phone()
    {
        return $this->hasOne('Phone');
    }

     public function getContactDetails()
    {
        return $this->hasOne('ContactDetails');
    }

     public function getHabit()
    {
        return $this->hasOne('Habit');
    }

    public function ethnicityPref()
    {
        return $this->hasMany('EthnicityPref');
    }

    public function birthFatherPref()
    {
        return $this->hasMany('BirthFatherPref');
    }

    public function ageGroupPref()
    {
        return $this->hasMany('AgeGroupPref');
    }

    public function child()
    {
        return $this->hasMany('Child');
    }

}
