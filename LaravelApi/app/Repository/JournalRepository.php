<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Journal;

/**
 * Description of ParentService
**/
class JournalRepository {

   
    private $account_id;

    public function __construct($account_id) {
       $this->setOwnerId($account_id);

    }

     public  function getOwnerId() {
       return $this->account_id;
    }
    public  function setOwnerId($account_id) {
         $this->accountId = $account_id;
    }   
    
   public function getJournalDetails(){
         try{
            $journal=new Journal;
            $journalDetails =$journal->where('account_id', '=', $this->accountId)
                                    ->where('PostStatus','=','approval')
                                    ->where('allowView','=',3)
                                    ->get();  
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
   public function getJournalsById(){
         try{
            $journal=new Journal;
            $journalDetails =$journal->where('account_id', '=', $this->accountId)
                                    ->where('PostStatus','=','approval')
                                    ->where('allowView','=',3)
                                    ->first();  
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
   public function getJournalsByTitle(){
         try{
            $journal=new Journal;
            $journalDetails =$journal->where('account_id', '=', $this->accountId)
                                    ->where('PostStatus','=','approval')
                                    ->where('allowView','=',3)
                                    ->first();  
            return $journalDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
   
}



