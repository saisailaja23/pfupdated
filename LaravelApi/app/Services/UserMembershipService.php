<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\MembershipRepository;
/**
 * Description of ParentService
**/

class UserMembershipService{

    private $accountId;
        
    public function __construct($accountId) {
       $this->setAccoountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccoountId($accountId) {
         $this->accountId = $accountId;
    } 
    


   public function saveMembership(){
      try{
       
      }
      catch(\Exception $e){
               //Add Exception here
      } 
    
    }

    
    
}


    
