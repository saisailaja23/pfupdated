<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\UserMembershipRepository;
use App\Services\UtilityService;
use App\Repository\AccountRepository;
use App\Repository\MembershipRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of UserMembershipService
**/
class UserMembershipService{
    private $accountId;
    private $idLevel;
    private $transactionId;
        
    public function __construct($accountId) {
       $this->setAccountId($accountId);      
    }
    
    public  function getAccountId() {
         return $this->accountId;
    }
    public  function getIdLevel() {
         return $this->idLevel;
    }
    public  function getTransactionId() {
         return $this->transactionId;
    }    

    public  function setAccountId($accountId) {
           $this->accountId = $accountId;
    }  
    public  function setIdLevel($idLevel) {
           $this->idLevel = $idLevel;
    }   
    public  function setTransactionId($transactionId) {
           $this->transactionId = $transactionId;
    }   
    
    

    public function saveMembership(){

  /*Save Membership*/
   public function saveMembership(){
      try{
      		 $userMemberObj=new UserMembershipRepository($this->accountId);
      		 $accountObj=new AccountRepository($this->accountId);
      		 if($accountExist=$accountObj->getAccountDetails()){
      			 $memberobj=new MembershipService($this->idLevel);
      			 if($membershipExist=$memberobj->getMembershipDetails()){
        			  $planPeriod=$memberobj->getMembershipPeriod();
        				$startDate=date('Y-m-d');
        				$endDate= date('Y-m-d',date(strtotime("+".$planPeriod." day", strtotime($startDate))));
        				$upgradeDate=date('Y-m-d');
        				$userMemberObj->setIdLevel($this->idLevel);
        				$userMemberObj->setTransactionId($this->transactionId);
        				$userMemberObj->setStartDate($startDate);
        				$userMemberObj->setEndDate($endDate);
        				$userMemberObj->setUpgradeDate($upgradeDate);
        				$save=$userMemberObj->save();
                return $save;
      			 }else{
      				throw new ParentFinderException('membership_not_found',$e->getMessage());
      			}
      		 }else{
      			 throw new ParentFinderException('user_not_found',$e->getMessage());
      		 }
		}
    
      catch(\Exception $e){
              //Throwing default Exceptions here
      } 
    
    }

  public function getMembership($account_id){

     try {
       
       $userMemberObj=new UserMembershipRepository($this->accountId);
       $memberDetails=$userMemberObj->getMembershipDetails($account_id);
       if(count($memberDetails)!="")
       {
        return $memberDetails;
       }
       else
       {
      
       //throw new ParentFinderException('membership_not_found');
       }
      } catch (Exception $e) {
       
        //Throwing default Exceptions here
       }
  }
    
    
}
    