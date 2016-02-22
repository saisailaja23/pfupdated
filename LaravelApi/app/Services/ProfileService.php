<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;

use App\Repository\ProfileRepository;
use App\Repository\EthnicityRepository;

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
    private $ethnicityVal;
    private $ethnicityId;
    private $religion;
    private $religionId;
    private $waiting;    
    private $waitingId;

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
        return $this->ethnicityVal;
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
        $this->waitingId=$profileDetails->waiting;
        $this->religionId=$profileDetails->religion_id;
        $ethnicity=new EthnicityRepository($this->ethnicityId);
        $ethnicityDetails=$ethnicity->getEthnictyDetails();
        $this->ethnicityVal=$ethnicityDetails->ethnicity;
        return $this;       
    }  


        
    
}
