<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\JournalRepository;
/**
 * Description of ParentService
**/

class JournalService{

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
   
    public function getJournals() {
        $journalsObj=new JournalRepository($this->accountId);
        if($journalDetails=$journalsObj->getJournalDetails()){
            return $journalDetails;
        } 
    }

    public function getJournalsById(){
        $journalsObj=new JournalRepository($this->journalid);
        if($journalDetails=$journalsObj->getJournalsById()){
            return $journalDetails;
        }
    } 
    public function getJournalsByTitle(){
        $journalsObj=new JournalRepository($this->journalid);
        if($journalDetails=$journalsObj->getJournalsByTitle()){
            return $journalDetails;
        }
    } 
    
}


    
