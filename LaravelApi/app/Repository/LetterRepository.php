<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\Letter;
use App\Models\LetterSort;

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
     public  function setLabel($label) {
         $this->label= $label;
    }  
     
     public  function setDescription($description) {
         $this->description= $description;
    }  
    public  function setSlug($slug) {
         $this->slug= $slug;
    }  
    public  function setAccountid($accountid) {
         $this->accountid= $accountid;
    }  
    
     public  function setImage($image) {
         $this->image= $image;
    }  
     public  function setSortorder($sortorder) {
         $this->sortorder= $sortorder;
    }  
     public  function setIsdefault($isdefault) {
         $this->isdefault= $isdefault;
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



 public function getSeo($slug){
         try{

            $letter=new Letter;
           $letterDetails =$letter->where('slug', '=', $slug)
                                   ->get();  
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
                                    ->orderBy('sort_order', 'asc')
                                   ->get();  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

    public function getLettersById($account_id,$letter_id){
        try{
            $letter=new Letter;
            $letterDetails =$letter->select('id')
                                    ->where('account_id', '=', $account_id)
                                    ->where('id', '=', $letter_id)
                                   ->get();  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
        } 
   }

   public function getSortedLetters($account_id){
      try{
            $letterSort=new LetterSort;
            $letterDetails =$letterSort->select('letter_id')
                                  ->where('account_id', '=', $account_id)
                                  ->orderBy('order_by', 'asc')
                                  ->get();  
                                  
            return $letterDetails;
        }catch(\Exception $e){
             //Add Exception here
      } 
   }
  
    /*  
        *   Save Lettere details 
        *   @return boolean $status
    *       
    */
  public function insertLetter()
    {
     try{
      $letterObj=new Letter;
      $status=$letterObj->insert(array('label'=>$this->label,
                                          'description'=>$this->description,
                                          'account_id'=>$this->accountid,
                                          'slug'=>$this->slug,
                                          'img'=>$this->image,
                                          'sort_order'=>$this->sortorder,
                                          'isDefault'=>$this->isdefault
                                         ) );
     return $status;
   }
   catch(\Exception $e){
             //Add Exception here
      } 
  
   }
}



