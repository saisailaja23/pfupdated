<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\JournalRepository;
/**
 * Description of ParentService
**/

class JournalService{

    private $journalId; 
    private $journalCaption;
    private $journalPhoto;
        
    public function __construct($journalId) {
       $this->setJournalId($journalId);      
    }
    
    public  function getJournalId() {
       return $this->journalId;
    }

    public  function setJournalId($journalId) {
         $this->journalId = $journalId;
    } 

    public  function getJournalCaption() {
       return $this->journalCaption;
    }
    public  function getJournalPhoto() {
       return $this->journalPhoto;
    }
    public  function getJournalText() {
       return $this->journalText;
    }
    public  function getJournalUri() {
       return $this->journalUri;
    }  
   
    public function getJournal() {
        $journalsObj=new JournalRepository($this->journalId);
        $journalDetails=$journalsObj->getJournalDetails();
        $this->journalCaption=$journalDetails->PostCaption;
        $this->journalPhoto=$journalDetails->PostPhoto;
        $this->journalUri=$journalDetails->PostUri;
        $this->journalText=$journalDetails->PostText;
        return $this;
         
    }


    public function saveJournal(){
        //Insert function...
    }

    
    
}


    
