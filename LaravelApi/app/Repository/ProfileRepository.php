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
    
    /*Get Couple Profile */
     public  function getCouple($accountId){   
        try{

            $couple=new Profiles;
            $coupleDetails =$couple->where('accounts_id', '=',$accountId) 
                                ->where('profile_id', '!=',$this->profileId)
                                ->first();   
            return $coupleDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    } 

    /* Get all profiles */
    public  function getAllProfiles(){   
        try{
            $profiles=new Profiles;
            $profileDetails =$profiles->get();       
            return $profileDetails;print_r($profileDetails);
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    } 

    /* Get a single profiles */
   
    public  function getProfile(){   
        try{
            $profiles=new Profiles;
            $profileDetails =$profiles->where('profile_id', '=',$this->profileId)->first();       
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }    
        
    
}
