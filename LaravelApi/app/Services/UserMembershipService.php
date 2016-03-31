<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\UserMembershipRepository;
use App\Services\UtilityService;
/**
 * Description of UserMembershipService
**/
class UserMembershipService{
    private $accountId;
        
    public function __construct($accountId) {
       $this->setAccountId($accountId);      
    }
    
    public  function getAccoountId() {
       return $this->accountId;
    }
    public  function setAccountId($accountId) {
         $this->accountId = $accountId;
    } 
    
   public function saveMembership($member){
      try{
		 $userMemberObj=new UserMembershipRepository($this->accountId);
		 $accountObj=new UtilityService(null);
		 if($accountExist=$accountObj->getUsernameByAccountId();){
			 $memberobj=new MembershipRepository($this->accountId);
			 if($membershipExist=$memberobj->getMembershipDetails($member['idlevel'])){
				 //get Plan days
				 $startDate='';
				 $endDate='';
				 $upgradeDate='';
				 $userMemberObj->setIdLevel($member['idlevel']);
				 $userMemberObj->setTransactionId($member['transaction_id']);
				 $userMemberObj->setStartDate($startDate) ;
				 $userMemberObj->setEndDate($endDate) ;
				 $userMemberObj->setUpgradeDate($upgradeDate) ;
				 $save=$userMemberObj->save();
			 }else{
				throw new ParentFinderException('membership_not_found',$e->getMessage());
			}
		 }else{
			 throw new ParentFinderException('user_not_found',$e->getMessage());
		 }
		}
      }
      catch(\Exception $e){
               //Throwing default Exceptions here
      } 
    
    }
    
    
}
    