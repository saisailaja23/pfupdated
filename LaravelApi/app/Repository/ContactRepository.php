<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactDetails;
/**
 * Description of ParentService
**/
class ContactRepository {   
    
	private $accountId; 
  private $regionId; 
  private $countryId; 
  private $stateId;
   private $city; 
    private $zip;
    private $phonenumber; 
    private $address1;
	
    public function __construct($accountId) {
         $this->setProfileId($accountId);
    }
	
    public  function getProfileId() {
       return $this->accountId;
    }
    public  function setProfileId($accountId) {
         $this->accountId = $accountId;
    }
	
    public  function getCountry() {
       return $this->countryId;
    }
    
    public function getStateId(){        
        return $this->stateId;
    }
     public  function getRegionId() {
      return $this->regionId;
    }
  
    
    public  function setStateId($stateId) {
       $this->stateId=$stateId;
    }
    public  function setCountryId($countryId) {
       $this->countryId=$countryId;
    }
    public  function setRegionId($regionId) {
       $this->regionId=$regionId;
    }
   public  function setCity($city) {
       $this->city=$city;
    }
    public  function setZip($zip) {
       $this->zip=$zip;
    }
     public  function setPhonenumber($phonenumber) {
       $this->phonenumber=$phonenumber;
    }
    public  function setAddress1($address1) {
       $this->address1=$address1;
    }

  
    /* Get Waiting */
    public function getContactDetails() {
        try{
            $contactObj=new ContactDetails;
            $contcatDetails =$contactObj->where('Account_id', '=',$this->accountId)->first();
            return $contcatDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
      public function getContactByRegion($regionId){
         try{
            $contactObj=new ContactDetails;
            $contcatDetails =$contactObj->where('Region', '=',$regionId)
                                ->get();
            return $contcatDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
     
      public function getContactByState($stateId){
      
         try{
            $contactObj=new ContactDetails;
            $contcatDetails =$contactObj->where('State', '=',$stateId)
                                ->get(); 
                                return $contcatDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    } 

    /*  
        *   Save Contact details On registration
        *   @return boolean $saveContact
    *       
    */
    public function saveContactDetails(){       
      try{
            $contactObj=new ContactDetails;
            $saveContact=$contactObj->insert(
                                        array('State'=>$this->stateId,
                                            'Country'=>$this->countryId,
                                            'Region'=>$this->regionId,
                                            'Account_id'=>$this->accountId
                                            )
                                    );
            return $saveContact;
      } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    } 


    public function updateContacts()
    {

     try{
      $contactObj=new ContactDetails;
     $updateDetails=$contactObj->where('Account_id', $this->accountId)
                               ->update(['State'=>$this->stateId,
                                         'Country'=>$this->countryId,
                                          'Region'=>$this->regionId,
                                          'City'=>$this->city,
                                          'Zip'=>$this->zip,
                                          'mobile_num'=>$this->phonenumber,
                                          'StreetAddress'=>$this->address1
                                         ]

                                          );
        return $updateDetails;
        } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    }


   
}