<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kids;
/**
 * Description of ParentService
**/
class KidsRepository {   
    
	private $child; 
    private $type; 
    private $accountId;
    private $children;
	
    public function __construct($child) {
         $this->setChild($child);
    }
	
    public  function getChild() {
       return $this->child;
    }
    public  function setChild($child) {
         $this->child = $child;
    } 

    public  function setType($type) {
         $this->type = $type;
    } 
    public  function setAccountId($accountId) {
         $this->accountId= $accountId;
    } 

     public  function setNoOfChildren($children) {
         $this->children= $children;
    } 
  
    /* Get Waiting */
    public function getChildDetails() {
        try{
            $childObj=new Child;
            $childDetails =$childObj->where('Number_of_childern', '=',$this->child)
                                ->get();
            return $childDetails;
        }catch(\Exception $e){
            echo "1";
             //Add Exception here
        }  
    }
    
   
}