<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\States;
/**
 * Description of ParentService
**/
class StateRepository {   
    
	private $stateId; 
	
    public function __construct($stateId) {
         $this->setStateId($stateId);
    }
	
    public  function getStated() {
       return $this->stateId;
    }
    public  function setStateId($stateId) {
         $this->stateId = $stateId;
    }    
    
  
    /* Get Waiting */
    public function getStateDetails() {
        try{
            
            $statetObj=new States;
            $stateDetails =$statetObj->where('state_id', '=',$this->stateId)->first();
            return $stateDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
     public function getStateById($state) {
        try{
            $statetObj=new States;
            $stateDetails =$statetObj->where('state', '=',$state)->first();
            return $stateDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
    
    public function getStatesDetails($country_id) {
        try{
           
            $statetObj=new States;
            $stateDetails=$statetObj->where('country_id', '=', $country_id)->get();
            return $stateDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
        
    
    

}