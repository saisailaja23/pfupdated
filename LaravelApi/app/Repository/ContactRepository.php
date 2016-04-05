<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactDetails;
/**
 * Description of ParentService
**/
class ContactRepository {   
    
	private $accountId; 
	
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
       return $this->country;
    }
    
    public function getState(){        
        return $this->state;
    }
     public  function getRegion() {
      return $this->region;
    }
  
    
    public  function setState($state) {
       $this->state=$state;
    }
    public  function setCountry($country) {
       $this->country=$country;
    }
    public  function setRegion($region) {
       $this->region=$region;
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


    public function saveContactDetails(){       
      try{
            $contactObj=new ContactDetails;
            $saveContact=$contactObj->insert(
                                        array('State'=>$this-state(),
                                            'Country'=>$this->country(),
                                            'Region'=>$this->region(),
                                            'Account_id'=>$this->accountId()
                                            )
                                    );
            return $saveContact->id;
      } 
      catch(\Exception $e){
            //Throwing default Exceptions here

      }
    }  
    
}