<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\ContactRepository;
use App\Repository\CountryRepository;
use App\Repository\StateRepository;
use App\Repository\ReligionRepository;
use App\Repository\RegionRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of ContactService
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
       $this->setAccountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccountId($accountId) {
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

    public  function setStateId($stateId) {
       $this->stateId=$state;
    }
    public  function setCountryId($countryId) {
       $this->countryId=$countryId;
    }
    public  function setRegionId($regionId) {
       $this->regionId=$regionId;
    }

    /* Get Contact Details */
    public function getContactDetails() {
        try{
        $contacts=new ContactRepository($this->accountId);
        if($contactDetails=$contacts->getContactDetails()){
            $this->countryId=$contactDetails->Country;          
            $this->stateId=$contactDetails->State;
            $this->mobileNumber=$contactDetails->mobile_num;
            $countryObj=new CountryRepository($this->countryId);
            if($countries=$countryObj->getCountryDetails()){
                $this->country=$countries->country;
            }
            $stateObj=new StateRepository($this->stateId);
            if($states=$stateObj->getStateDetails()){
                 $this->state=$states->State;
            } 
            return $this;
        } 
    }
    catch(\Exception $e){
            throw new ParentFinderException('contact_not_found',$e->getMessage());
        } 
    }

     /*  
        *   Save Contact details On registration
        *   @return boolean $saveContact
    *       
    */

     /* Add Contact Details */
     public function saveContactDetails(){       
      try{
            $contactObj=new ContactDetails;
            $contactObj->
            $saveContact=$contactObj->saveContactDetails();
            return $saveContact;
      } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    }   
    
    
}


    
