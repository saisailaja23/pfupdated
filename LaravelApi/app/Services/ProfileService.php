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
use App\Repository\ContactRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
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
    private $coupleFirstName;
    private $coupleLastName;
    private $coupleDob;
    private $coupleFaithId;
    private $coupleEthnicityId;
    private $coupleReligionId;
    private $coupleWaitingId;
    private $coupleEthnicity;
    private $coupleFaith;
    private $coupleWaiting;
    private $coupleGender;
    private $avatar;
    private $profileIds;
	private $country;
	private $state;
	private $countryId;
	private $stateId;
	
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
    public function getCoupleFirstName() {
        return $this->coupleFirstName;
    } 
    public function getCoupleLastName(){        
        return $this->coupleLastName;
    }
    public function getCoupleDob() {
        return $this->coupleDob;
    }
    public function getCoupleFaithId(){        
        return $this->coupleFaithId;
    }
    public function getCoupleEthnicityId(){        
        return $this->coupleEthnicityId;
    }
    public function getCoupleReligionId() {
        return $this->coupleReligionId;
    }
    public function getCoupleWaitingId() {
        return $this->coupleWaitingId;
    }
    public function getCoupleEthnicity() {
        return $this->coupleEthnicity;
    } 
    public function getCoupleFaith() {
        return $this->coupleFaith;
    }   
    public function getCoupleWaiting() {
        return $this->coupleWaiting;
    }
    public function getCoupleGender() {
        return $this->coupleGender;
    }
    public function getAvatar() {
        return $this->avatar;
    }   
    public function getProfileIds() {
        return $this->profileIds;
    }  
	public function getCountryId() {
        return $this->countryId;
    }  
	public function getStateId() {
        return $this->stateId;
    }
	public function getCountry() {
        return $this->country;
    }  
	public function getState() {
        return $this->state;
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
        $this->avatar=$profileDetails->Avatar;
        $ethnicity=new EthnicityRepository($this->ethnicityId);
        if($ethnicityDetails=$ethnicity->getEthnicityDetails())
        $this->ethnicity=$ethnicityDetails->ethnicity;
        $faith=new FaithRepository($this->faithId);
        if($faithDetails=$faith->getFaithDetails())
        $this->faith=$faithDetails->faith;
        $waiting=new WaitingRepository($this->waitingId);
        if($waitingDetails=$waiting->getWaitingDetails())
        $this->waiting=$waitingDetails->waiting;
	
		/*Get Contacts */
		$contacts=new ContactRepository($this->accountId);
		if($contactDetails=$contacts->getContactDetails()){
			$this->countryId=$contactDetails->Country;			
			$this->stateId=$contactDetails->State;
			$countryObj=new CountryRepository($this->countryId);
			$countries=$countryObj->getCountryDetails();
			$this->country=$countries->country;
			$stateObj=new StateRepository($this->stateId);
			$states=$stateObj->getStateDetails();
			//$this->state=$states->state;
		}
		
        /*Get Couple*/        
        if($coupleDetails=$profile->getCouple($this->accountId))
        $this->coupleId=$coupleDetails->profile_id;
        if($this->coupleId){
            $couple=new ProfileRepository($this->coupleId);  
            $coupleDetails=$couple->getProfile();
            $this->coupleFirstName=$coupleDetails->first_name;
            $this->coupleLastName=$coupleDetails->last_name;
            $this->coupleDob=$coupleDetails->dob;
            $this->coupleFaithId=$coupleDetails->faith_id;
            $this->coupleEthnicityId=$coupleDetails->ethnicity_id;
            $this->coupleWaitingId=$coupleDetails->waiting_id;
            $this->coupleReligionId=$coupleDetails->religion_id;
            $this->coupleGender=$coupleDetails->gender;
            $ethnicity=new EthnicityRepository($this->coupleEthnicityId);
            if($ethnicityDetails=$ethnicity->getEthnicityDetails())
            $this->coupleEthnicity=$ethnicityDetails->ethnicity;
            $faith=new FaithRepository($this->coupleFaithId);
            if($faithDetails=$faith->getFaithDetails())
            $this->coupleFaith=$faithDetails->faith;
            $waiting=new WaitingRepository($this->coupleWaitingId);
            if($waitingDetails=$waiting->getWaitingDetails())
            $this->coupleWaiting=$waitingDetails->waiting;
		
            
        }        
        return $this;       
    }  
    /*Get all profiles */
    public  function getAllProfiles(){ 
        $profile=new ProfileRepository($this->profileId);  
        $profileDetails=$profile->getAllProfiles();
        foreach ($profileDetails as $ProfileID) {
            $this->profileIds[]=$ProfileID->profile_id;
        }
        return $this;
    }
	
	/*Get Contact details */
	public function getContactDetails(){
		$contacts=new ContactRepository($this->profileId);
		$contactDetails=$contacts->getContactDetails();
		foreach ($contactDetails as $contact) {
            $this->country=$contact->country;
			$this->state=$contact->state;
        }
        return $this;
	}
        
    
}