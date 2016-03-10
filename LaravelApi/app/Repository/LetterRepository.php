<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\Letter;

/**
 * Description of ParentService
**/
class LetterRepository {

   
    private $letter_id;

    public function __construct($letter_id) {
       $this->setLetterId($letter_id);

    }

     public  function getLetterId() {
       return $this->letter_id;
    }
    public  function setLetterId($letter_id) {
         $this->letter_id = $letter_id;
    }   
    
   public function getLetters(){
         try{
            $letter=new Letter;
            $letterDetails =$letter->where('id', '=', $this->letter_id)
                                   ->first();  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

   public function getDefaultLettersByAccount($account_id){
        try{
            $letter=new Letter;
            $letterDetails =$letter->where('account_id', '=', $account_id)
                                    ->where('isDefault', '=', 1)
                                   ->first();  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

   public function getLettersByAccount($account_id){
        try{
            $letter=new Letter;
            $letterDetails =$letter->select('id')
                                    ->where('account_id', '=', $account_id)
                                   ->get();  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }
  
   
}



