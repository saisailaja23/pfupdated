<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Eprofile;

/**
 * Description of ParentService
**/
class EprofileRepository {

   
    private $account_id;

    public function __construct($account_id) {
       $this->setAccountId($account_id);

    }

     public  function getAccountId() {
       return $this->account_id;
    }
    public  function setAccountId($account_id) {
         $this->account_id = $account_id;
    }   
    
    public function getFlipbookId(){
         try{
            $eprofileobj=new Eprofile;
            $flipbookdetails =$eprofileobj->where('account_id', '=',$this->account_id)
                                  ->where('title', '=','E-book Profile')
                                  ->first();       
            return  $flipbookdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }

    public function getFlipbookDetails($id){
        try{
            $eprofileobj=new Eprofile;
            $flipbookdetails =$eprofileobj->where('id', '=',$id)
                                  ->where('title', '=','E-book Profile')
                                  ->first();       
            return  $flipbookdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 

    }

     public function getEpubDetails(){
           try{
         
            $eprofileobj=new Eprofile;
            $Epubdetails =$eprofileobj->where('owner_id', '=',$this->account_id)
                                  ->where('title', '=','E-PUB Profile')
                                   ->where('content', '!=','')
                                  ->first();  
                                  return $Epubdetails;
            }
             catch(\Exception $e){
             //Add Exception here
              } 

      }
   
    }



