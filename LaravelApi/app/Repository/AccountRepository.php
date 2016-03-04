<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Account;

/**
 * Description of ParentService
**/
class AccountRepository {

   
   
    private $accountId;

    public function __construct($accountId) {
         $this->setAccountId($accountId);
    }
    
    public  function getAccountId() {
       return $this->accountId;
    }

    public  function setAccountId($accountId) {
         $this->accountId = $accountId;
    } 

   
    

    public  function getAccountDetails(){   
        try{
            $account=new Account;
            $accountDetails =$account->where('account_id', '=', $this->accountId)->first();  
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }
   
    public  function getAccount(){   
        try{
            $account=new Profiles;
            $accountDetails =$account->where('profile_id', '=',$this->profileId)->first();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }
     public  function getProfileIds(){   
        try{
            $profile=new Profiles;
            $profileDetails =$profile->where('accounts_id', '=',$this->accountId)
                                    ->get();       
            return $profileDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }

    public  function getAllAccounts(){   
        try{
            $account=new Account;
            $accountDetails =$account->get();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }

    
        
    
}
