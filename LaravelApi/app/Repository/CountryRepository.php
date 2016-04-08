<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Countries;
/**
 * Description of ParentService
**/
class CountryRepository {   
    
	private $countryId;
	
    public function __construct($countryId) {
         $this->setCountryId($countryId);
    }
	
    public  function getCountryId() {
       return $this->countryId;
    }
    public  function setCountryId($countryId) {
         $this->countryId = $countryId;
    }
	
    
    /* Get Waiting */
    public function getCountryDetails() {
        try{
            $countryObj=new Countries;
            $countryDetails =$countryObj->where('country_id', '=',$this->countryId)->first();
            return $countryDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
    
   public function getCountrysDetails() {
        try{
          
            $countrysObj=new Countries;
            $countryDetails=$countrysObj->get();
            return $countryDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
     
    
}