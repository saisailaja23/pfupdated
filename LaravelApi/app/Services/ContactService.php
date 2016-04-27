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
              $states;
                 $this->state=$states->State;
            } 
            return $this;
        } 
    }
    catch(\Exception $e){
           //throw new ParentFinderException('contact_not_found',$e->getMessage());
        } 
    }

     /*  
        *   Save Contact details On registration
        *   @return boolean $saveContact
    *       
    */

     /* Add Contact Details */
     public function saveContactDetails($data){       
      try{
        $contacts=new ContactRepository(null);
        $contacts->setProfileId($data['account_id']);
        $contacts->setCountryId($data['Country']);
        $contacts->setStateId($data['State']);
        $contacts->setRegionId($data['Region']);
        $saveContact=$contacts->saveContactDetails();
        return $saveContact;
      } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    } 

    public function updateContact($data)
    {
      try {
          
         $contacts=new ContactRepository(null);
         $contacts->setProfileId($data['account_id']);
         $contacts->setCountryId($data['Country']);
         $contacts->setStateId($data['State']);
         $contacts->setRegionId($data['Region']);
         $contacts->setCity($data['City']);
         $contacts->setZip($data['Zip']);
         $contacts->setPhonenumber($data['phonenumber']);
         $contacts->setAddress1($data['address1']);
         $updateStatus=$contacts->updateContacts();
           return $updateStatus;
            } catch (Exception $e) {
           //throw new ParentFinderException('contact_not_found',$e->getMessage());
      }
    }
    
}


    
