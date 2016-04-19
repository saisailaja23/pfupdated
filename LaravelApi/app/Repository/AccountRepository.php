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
    private $name;
    private $emailId;
    private $userName;
    private $status;
    private $roleId;
    private $createdAt;
    private $modifiedAt;
    private $password;
    private $agencyId;

    public function __construct($accountId) {
         $this->setAccountId($accountId);
    }
    
    public  function getAccountId() {
       return $this->accountId;
    }

    public  function setAccountId($accountId) {
         $this->accountId = $accountId;
    } 

    public  function getName() {
       return $this->name;
    }
    public  function getEmailId() {
       return $this->emailId;
    }
    public  function getUserName() {
       return $this->userName;
    }
    public  function getStatus() {
       return $this->status;
    }
    public  function getRoleId() {
       return $this->roleId;
    }
    public  function getCreatedAt() {
       return $this->createdAt;
    }
    public  function getModifiedAt($modifiedAt) {
       $this->modifiedAt=$modifiedAt;
    }
    public  function getPassword($password) {
       $this->password=$password;
    }
    public  function getAgencyId($agencyId) {
       $this->agencyId=$agencyId;
    }

    public  function setName($name) {
       $this->name=$name;
    }
    public  function setEmailId($emailId) {
       $this->emailId=$emailId;
    }
    public  function setUserName($userName) {
       $this->userName=$userName;
    }
    public  function setStatus($status) {
       $this->status=$status;
    }
    public  function setRoleId($roleId) {
       $this->roleId=$roleId;
    }
    public  function setCreatedAt($createdAt) {
       $this->createdAt=$createdAt;
    }

    public  function setModifiedAt($modifiedAt) {
       $this->modifiedAt=$modifiedAt;
    }
    public  function setPassword($password) {
       $this->password=$password;
    }
    public  function setAgencyId($agencyId) {
       $this->agencyId=$agencyId;
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
             //catch Exceptions here
        } 
          
    }

    public  function getAllAccounts(){   
        try{
            $account=new Account;
            $accountDetails =$account->take(6)->orderByRaw("RAND()")->get();       
            return $accountDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
          
    }

    public function saveAccountDetails(){       
      try{
            $accountObj=new Account;
            $saveAccount=$accountObj->insert(
                                        array('name'=>$this->name(),
                                            'emailid'=>$this->emailId(),
                                            'username'=>$this->userName(),
                                            'status'=>$this->status(),
                                            'role_id'=>$this->roleId(),
                                            'created_at'=>$this->createdAt(),
                                            'modified_at'=>$this->modifiedAt(),
                                            'password'=>$this->password(),
                                            'agency_id'=>$this->agencyId(),
                                            )
                                    );
            return $saveAccount->id;
      } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    }

    
    
}
