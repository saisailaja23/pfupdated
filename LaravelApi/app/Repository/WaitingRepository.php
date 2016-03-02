<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Waiting;

/**
 * Description of ParentService
**/
class WaitingRepository {

   
    private $waitingId;
    private $waiting; 

    public function __construct($waitingId) {
       $this->setWaitingId($waitingId);

    }
    
    public  function getWaitingId() {
       return $this->waitingId;
    }

    public  function setWaitingId($waitingId) {
         $this->waitingId = $waitingId;
    }

    public function getWaiting(){        
        return $this->waiting;
    }

    public function setWaiting($waiting){        
        $this->waiting=$waiting;
    }  
  
    /* Get Waiting */
    public function getWaitingDetails() {
        try{
            $waitingObj=new Waiting;
            $waitingDetails =$waitingObj->where('waiting_id', '=',$this->getWaitingId())->first();
            return $waitingDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }


        
    
}
