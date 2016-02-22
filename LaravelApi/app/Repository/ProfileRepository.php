<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;

/**
 * Description of ParentService
**/
class ProfileRepository {

   
    private $profileId;

    public function __construct($profileId) {
         $this->setProfileId($profileId);
    }
    
    public  function getProfileId() {
       return $this->profileId;
    }

    public  function setProfileId($profileId) {
         $this->profileId = $profileId;
    }   
    

    /* Get a single profiles */
   
    public  function getProfile(){   
        try{
            $profiles=new Profiles;
            $profileDetails =$profiles->where('accounts_id', '=',$this->profileId)->first();       
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }    
        
    
}
