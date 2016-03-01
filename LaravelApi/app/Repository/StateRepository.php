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
            $stateDetails =$statetObj->where('accounts_id', '=',$this->stateId)->first();
            return $stateDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }
        
    
}