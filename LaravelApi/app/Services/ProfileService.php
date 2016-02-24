<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;

use App\Repository\ProfileRepository;
use App\Repository\EthnicityRepository;
use App\Repository\FaithRepository;
use App\Repository\WaitingRepository;
use App\Repository\UserRepository;
/**
 * Description of ParentService
**/
class ProfileService {

   
    private $profileId;
    private $firstName;
    private $lastName;
    private $dob;
    private $faith;
    private $faithId;
    private $ethnicity;
    private $ethnicityId;
    private $religion;
    private $religionId;  
    private $waitingId;
    private $waiting;  
    private $gender;
    private $accountId;
    private $coupleId;

    public function __construct($profileId) {
       $this->setProfileId($profileId);

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
        return $this->ethnicity;
    } 

    public function getFaith() {
        return $this->faith;
    }   

    public function getWaiting() {
        return $this->waiting;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getAccountId() {
        return $this->accountId;
    }

    public function getCoupleId() {
        return $this->coupleId;
    }

   
    /* Get a single profiles */
   
    public  function getProfile(){
        $profile=new ProfileRepository($this->profileId);  
        $profileDetails=$profile->getProfile();
        $this->firstName=$profileDetails->first_name;
        $this->lastName=$profileDetails->last_name;
        $this->dob=$profileDetails->dob;
        $this->faithId=$profileDetails->faith_id;
        $this->ethnicityId=$profileDetails->ethnicity_id;
        $this->waitingId=$profileDetails->waiting_id;
        $this->religionId=$profileDetails->religion_id;
        $this->gender=$profileDetails->gender;
        $this->accountId=$profileDetails->accounts_id;

         /*Get Couple*/
        //$couple=new ProfileRepository($this->accountId);
        $coupleDetails=$profile->getCouple($this->accountId);
        $this->coupleId=$coupleDetails->profile_id;

        $ethnicity=new EthnicityRepository($this->ethnicityId);
        $ethnicityDetails=$ethnicity->getEthnicityDetails();
        $this->ethnicity=$ethnicityDetails->ethnicity;

        $faith=new FaithRepository($this->faithId);
        $faithDetails=$faith->getFaithDetails();
        $this->faith=$faithDetails->faith;

        $waiting=new WaitingRepository($this->waitingId);
        $waitingDetails=$waiting->getWaitingDetails();
        $this->waiting=$waitingDetails->waiting;

       

        return $this;       
    }  

    /*Get all profiles */

    public  function getAllProfiles(){ 
    }
        
    
}
