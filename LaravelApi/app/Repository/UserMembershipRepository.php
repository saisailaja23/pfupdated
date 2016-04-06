<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserMembership;

/**
 * Description of ParentService
**/
class UserMembershipRepository {

   
   
    private $accountId;
	private $idLevel;
	private $transactionId;
	private $startDate;
	private $endDate;
	private $upgradeDate;

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
	public  function getStartDate() {
        return $this->startDate;
    } 
	public  function getEndDate() {
         return $this->endDate ;
    }
	public  function getUpgradeDate() {
        return $this->upgradeDate;
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
	public  function setStartDate($startDate) {
         $this->startDate = $startDate;
    } 
	public  function setEndDate($endDate) {
         $this->endDate = $endDate;
    }
	public  function setUpgradeDate($upgradeDate) {
         $this->upgradeDate = $upgradeDate;
    }	
 
    /* Save membership */
   
    public  function save(){ 
        try{
    		$Membershipobj=new UserMembership;
    		$saveMember=$Membershipobj->insert(
                                        array('IDMember' =>$this->getAccountId(),
    										'IDLevel' => $this->getIdLevel(),
    										'TransactionID' => $this->getTransactionId(),
    										'DateStarts' => $this->getStartDate(),
    				 						'DateExpires' => $this->getEndDate(),
    										'Upgrade' => $this->getUpgradeDate()
                                            )
    					);  
    		return $saveMember;
        }
        catch(\Exception $e){
              //Throwing default Exceptions here
      } 
    }
    

    
        
    
}
