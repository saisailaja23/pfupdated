<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kids;
/**
 * Description of ParentService
**/
class KidsRepository {   
    
	private $child; 
  
	
    public function __construct($child) {
         $this->setChild($child);
    }
	
    public  function getChild() {
       return $this->child;
    }
    public  function setChild($child) {
         $this->child = $child;
    } 

   
  
    /* Get Kids */
    public function getKidsDetails() {
        try{
            $kidsObj=new Kids;
            $kidsDetails =$kidsObj->get();
            return $kidsDetails;
        }catch(\Exception $e){
        
             //Add Exception here
        }  
    }
    
   
}