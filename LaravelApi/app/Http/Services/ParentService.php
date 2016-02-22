<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\User;
use App\Http\Models\Profiles;
use App\Http\Models\Ethnicity;

/**
 * Description of ParentService
**/
class ParentService {

   
    private $profileId;
    private $firstName;
    private $lastName;
    private $dob;
    private $faith;
    private $faithId;
    private $ethnicityVal;
    private $ethnicityId;
    private $religion;
    private $religionId;
    private $waiting;    
    private $waitingId;

    public function __construct($profileId) {
        $this->setProfileId($profileId);
        $this->setFirstName();
        $this->setLastName();        
        $this->setDob();
        $this->setFaithId();
        $this->setEthnicityId();
        $this->setReligionId();
        $this->setWaitingId();
        $this->setEthnicty();
    }
    
    public  function getProfileId() {
       return $this->profileId;
    }

    public  function setProfileId($profileId) {
         $this->profileId = $profileId;
    }

    public function getFirstName(){        
        return $this->firstName;
    }

    public function getLastName(){        
        return $this->lastName;
    }

    public function getDob() {
        return $this->dob;
    }

    public function getFaithId(){        
        return $this->faithId;
    }

    public function getEthnicityId(){        
        return $this->ethnicityId;
    }

    public function getReligionId() {
        return $this->religionId;
    }

    public function getWaitingId() {
        return $this->waitingId;
    }

    public function getEthnicity() {
        return $this->ethnicityVal;
    }


    public  function setFirstName() {
        try{
            $profiles=new Profiles;
            $firstName =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->first_name;       
            $this->firstName=$firstName;
        }catch(\Exception $e){
             //Add Exception here
        } 
           
    }

    public function setDob() {
       try{
            $profiles=new Profiles;
            $dob =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->dob;       
            $this->dob=$dob;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }
    
    public  function setLastName() {
        try{
            $profiles=new Profiles;
            $lastName =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->last_name;
            $this->lastName=$lastName;
        }
        catch(\Exception $e){
             //Add Exception here
        }           
       
    }

    public  function setFaithId() {
        try{
            $profiles=new Profiles;
            $faithId =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->faith_id;
            $this->faithId=$faithId;
        }catch(\Exception $e){
             //Add Exception here
        }       
    }

    public  function setEthnicityId() {
        try{
            $profiles=new Profiles;
            $ethnicityId =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->ethnicity_id;
            $this->ethnicityId=$ethnicityId;
        }catch(\Exception $e){
             //Add Exception here
        }       
    }

    public  function setReligionId() {
        try{
            $profiles=new Profiles;
            $religionId =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->religion_id;
            $this->religionId=$religionId;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }

    public function setWaitingId() {
         try{
            $profiles=new Profiles;
            $waitingId =$profiles->where('accounts_id', '=',$this->getProfileId())->first()->waiting;
            $this->waitingId=$waitingId;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }

    public function setEthnicty() {
        try{
            $ethnicityObj=new Ethnicity;
            $ethnicityVal =$ethnicityObj->where('ethnicity_id', '=',$this->getEthnicityId())->first()->ethnicity;
            $this->ethnicityVal=$ethnicityVal;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }

    /* Get a single profiles */
   
    public  function getProfile(){   
        $this->first_name=$this->getFirstName();
        $this->last_name=$this->getLastName();        
        $this->date_of_birth=$this->getDob();
        $this->faith_id=$this->getFaithId();
        $this->religion_id=$this->getReligionId();
        $this->waiting_id=$this->getWaitingId();
        $this->ethnicity=$this->getEthnicity();
        return $this;       
    }    
        
    
}
