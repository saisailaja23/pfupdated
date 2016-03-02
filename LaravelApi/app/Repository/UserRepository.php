<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;

/**
 * Description of ParentService
**/
class UserRepository {

   
    private $profileId;
    private $accountId;

    public function __construct($profileId) {
         $this->setProfileId($profileId);
    }
    
    public  function getProfileId() {
       return $this->profileId;
    }

    public  function setProfileId($profileId) {
         $this->profileId = $profileId;
    } 

    public  function getAccountId() {
       return $this->accountId;
    } 
    

    /* Get a single profiles */
   
    public  function getAccount(){   
        try{
            $account=new Profiles;
            $accountDetails =$account->where('profile_id', '=',$this->profileId)->first();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }

    
        
    
}
