<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\PdfTemplate;
use App\Repository\ProfileRepository;
use App\Repository\EthnicityRepository;
use App\Repository\FaithRepository;
use App\Repository\WaitingRepository;
use App\Repository\UserRepository;
use App\Repository\ContactRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\Repository\ReligionRepository;
use App\Repository\RegionRepository;
use App\Repository\ChildRepository;
use App\Repository\EprofileRepository;
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
     private $creationDate;

	
	
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
    
    
    


    
    public  function setFirstName($firstName) {
       $this->firstName=$firstName;
    }
    public  function setLastName($lastName) {
       $this->lastName=$lastName;
    }
    public  function setGender($gender) {
       $this->gender=$gender;
    } 
    public  function setAccountId($accountId) {
       $this->accountId=$accountId;
    }
    public  function setStatus($status) {
       $this->status=$status;
    }    
    public  function setCreatedAt($createdAt) {
       $this->createdAt=$createdAt;
    }
    public  function setModifiedAt($modifiedAt) {
       $this->modifiedAt=$modifiedAt;
    } 
     public  function setEthnicityId($ethnicityId) {
       $this->ethnicityId=$ethnicityId;
    } 
     public  function setEducationId($educationId) {
       $this->educationId=$educationId;
    } 

      public  function setDOB($dob) {
       $this->dob=$dob;
    } 
  public  function setReligionId($religionId) {
       $this->religionId=$religionId;
    } 
 
      	
	
    /* Get a single profiles */
   
    public  function getProfile(){
        try{
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
        $ethnicity=new EthnicityRepository($this->ethnicityId);
        if($ethnicityDetails=$ethnicity->getEthnicityDetails())
        $this->ethnicity=$ethnicityDetails->ethnicity;
        $faith=new FaithRepository($this->faithId);
        if($faithDetails=$faith->getFaithDetails())
        $this->faith=$faithDetails->faith;
        $waiting=new WaitingRepository($this->waitingId);
        if($waitingDetails=$waiting->getWaitingDetails())
        $this->waiting=$waitingDetails->waiting;		
           
        return $this;
        }
        catch(\Exception $e){
             //Throwing Exception here
        }        
    }

    /*Save Profile Details*/
    public function saveProfile(){
        try{
            $profileObj=new ProfileRepository();
            $profileObj->setFirstName($this->firstName);
            $profileObj->setLastName($this->lastName);
            $profileObj->setGender($this->gender);           
            $profileObj->setCreatedAt(getCurrentDateTime());
            $profileObj->setModifiedAt(getCurrentDateTime());
            $insertStatus=$profileObj->saveProfile();

        }catch(\Exception $e){
             //Throwing Exception here
        }
    }  
 public function updateProfile($data){
        try{
     
            $profileObj=new ProfileRepository(null);
            $profileObj->setAccountId($data['accounts_id']); 
            $profileObj->setProfileId($data['profile_id']); 
            $profileObj->setFirstName($data['firstNameSingle']);
            $profileObj->setGender($data['genderSingle']);
            $profileObj->setDOB($data['DOB']);   
            $profileObj->setEthnicityId($data['ethnicity']);   
            $profileObj->setReligionId($data['religion']); 
            $profileObj->setOccupation($data['occupation']); 
            $profileObj->setEducationId($data['education']);
            $profileObj->setWaitingId($data['waiting']);
            $profileObj->setFaithId($data['faith']);
         
            $updateStatus=$profileObj->updateProfile();
            return $updateStatus;

        }catch(\Exception $e){
             //Throwing Exception here
        }
    }  
    
	

   
    
}


    
