<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\JournalRepository;
use App\Exceptions\ParentFinderException;
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
    public  function getJournalDate() {
       return $this->journalDate;
    } 
   

    /*Get Journal*/
    public function getJournal() { 
        try{
        $journalsObj=new JournalRepository($this->journalId);
        if($journalDetails=$journalsObj->getJournalDetails()){
        $this->journalCaption=$journalDetails->PostCaption;
        $this->journalPhoto=$journalDetails->PostPhoto;
        $this->journalUri=$journalDetails->PostUri;
        $this->journalText=$journalDetails->PostText;
        $this->journalDate = $journalDetails->PostDate;
        return $this;
    }
    else{
         throw new ParentFinderException('journal_not_found');
    }
    }
    catch(\Exception $e){
          throw new ParentFinderException('journal_not_found');
        } 
         
    }


    /*Save Journal*/
    public function saveJournal(){
        //Insert function...
    }

    
    
}


    
