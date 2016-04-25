<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Account;
use App\Models\EmailTemplates;
use App\Exceptions\ParentFinderException;

/**
 * Description of ParentService
**/
class EmailTemplatesRepository {

   
    private $id;

    public function __construct($id) {
       $this->setId($id);

    }

     public  function getId() {
       return $this->id;
    }
    public  function setId($id) {
         $this->id = $id;
    }   
    

   public function getEmailTemplate($sTemplateName, $langId)
   {
     try{
            $templateObj=new EmailTemplates;
            $getDetails=$templateObj->where('Name', $sTemplateName)
                                    ->where('LangID', $langId)
                                    ->first();
                               
           return $getDetails;
          }catch(\Exception $e){
            //Add Exception here
          } 
   }
  
   
   
}



