<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\ContactRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\Repository\ReligionRepository;
use App\Repository\RegionRepository;
/**
 * Description of ParentService
**/

class ContactService {

    private $accountId; 
    private $country;
    private $state;  
    private $countryId;
    private $stateId;  
    private $regionId;
    private $region;
    private $streetAddress;
    private $city;  
    private $mobileNumber;
    private $homeNumber;
        
    public function __construct($accountId) {
       $this->setAccoountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccoountId($accountId) {
         $this->accountId = $accountId;
    } 

    public function getCountryId() {
        return $this->countryId;
    } 

    public function getStateId() {
        return $this->stateId;
    }

    public function getRegionId() {
        return $this->regionId;
    }

    public function getRegion() {
        return $this->region;
    }

    public function getCountry() {
        return $this->country;
    }  

    public function getState() {
        return $this->state;
    }

    public function getMobileNumber() {
        return $this->mobileNumber;
    }
    public function getHomeNumber() {
        return $this->homeNumber;
    }

    public function getContactDetails() {
        try{
        $contacts=new ContactRepository($this->accountId);
        if($contactDetails=$contacts->getContactDetails()){
            $this->countryId=$contactDetails->Country;          
            $this->stateId=$contactDetails->State;
            $this->mobileNumber=$contactDetails->mobile_num;
            $countryObj=new CountryRepository($this->countryId);
            $countries=$countryObj->getCountryDetails();
            $this->country=$countries->country;
            $stateObj=new StateRepository($this->stateId);
            $states=$stateObj->getStateDetails();
            $this->state=$states->State;
            return $this;
        } 
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    } 
    
    
}


    
