<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\JournalRepository;
/**
 * Description of ParentService
**/

class JournalService {

    private $journalid; 
        
    public function __construct($journalid) {
       $this->setAccoountId($journalid);      
    }
    
    public  function getAccoountId() {
       return $this->journalid;
    }
    public  function setAccoountId($journalid) {
         $this->journalid = $journalid;
    }    
   
    public function getJournals() {
        $journalsObj=new JournalRepository($this->journalid);
        if($journalDetails=$journalsObj->getJournalDetails()){
            return $this;
        } 
    }

    public function getJournalsById(){
        $journalsObj=new JournalRepository($this->journalid);
        if($journalDetails=$journalsObj->getJournalsById()){
            return $this;
        }
    } 
    public function getJournalsByTitle(){
        $journalsObj=new JournalRepository($this->journalid);
        if($journalDetails=$journalsObj->getJournalsById()){
            return $this;
        }
    } 
    
}


    
