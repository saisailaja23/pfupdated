<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PdfTemplate;

/**
 * Description of ParentService
**/
class PdfRepository {

   
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
    
   public function getPdfDetail(){
         try{
            $pdfobj=new PdfTemplate;
            $pdfdetails =$pdfobj->where('account_id', '=',$this->account_id)
                                  ->where('isDeleted', '=','N')
                                  ->where('isDefault', '=','Y')
                                  ->first();       
            return  $pdfdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }

     public function getPdfDetails($id){
         try{
            $pdfobj=new PdfTemplate;
            $pdfdetails =$pdfobj->where('template_user_id', '=',$id)
                                  ->where('isDeleted', '=','N')
                                  ->where('isDefault', '=','Y')
                                  ->first();       
            return  $pdfdetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
    }
   
    }



