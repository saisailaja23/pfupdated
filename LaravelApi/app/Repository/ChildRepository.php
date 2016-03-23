<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
/**
 * Description of ParentService
**/
class ChildRepository {   
    
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