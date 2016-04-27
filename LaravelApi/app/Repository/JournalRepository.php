<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Journal;

/**
 * Description of ParentService
**/
class JournalRepository {

   
    private $journal_id;

    public function __construct($journal_id) {
       $this->setJournalId($journal_id);

    }

     public  function getJournalId() {
       return $this->journal_id;
    }
    public  function setJournalId($journal_id) {
         $this->journal_id = $journal_id;
    }   
    
   public function getJournalDetails(){
         try{
            $journal=new Journal;
            $journalDetails =$journal->where('PostId', '=', $this->journal_id)
                                    ->where('PostStatus','=','approval')
                                    //->where('allowView','=',3)
                                    ->first();  
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }


public function getJournalSeo($slug){
         try{
           
            $journal=new Journal;
            $journalDetails =$journal->where('slug', '=', $slug)
                                     ->where('PostStatus','=','approval')
                                    ->get();  
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }


   
   public function getJournalsByAccount($accountId){
    try{
        $journal=new Journal;
        $journalDetails =$journal->select('PostId')
                                ->where('account_id', '=', $accountId)
                                ->where('PostStatus','=','approval')
                                //->where('allowView','=',3)
                                ->get(); 
        return $journalDetails;
    }catch(\Exception $e){
             //Add Exception here
        } 
   }

   public function getJournalsByTitle($accountId,$title){
         try{
            $journal=new Journal;

          $journalDetails =$journal->select('PostId')
                                    ->where('account_id', '=', $accountId)
                                    ->where('PostStatus','=','approval')
                                    ->where('PostCaption','like','%'.$title.'%')
                                    ->get();     
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

   public function getJournalsById($accountId,$journalId){
         try{
            $journal=new Journal;
            $journalDetails =$journal->select('PostId')
                                    ->where('account_id', '=', $accountId)
                                    ->where('PostStatus','=','approval')
                                    ->where('PostId','=',$journalId)
                                    ->get();                                  
                                   
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
   
   
}



