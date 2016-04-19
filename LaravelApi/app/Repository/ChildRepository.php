<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Child;
/**
 * Description of ParentService
**/
class ChildRepository {   
    
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
    
    public function updateChild() {
        try{
            $childObj=new Child;
            $status=$childObj->where('Accounts_id',$this->accountId)
                               ->update(['Type'=>$this->type,
                                          'Number_of_childern'=>$this->children
                                         ]
                                            
                                    );
              
            return $status;
        }catch(\Exception $e){
          
             //Add Exception here
        }  
    }    
    
}