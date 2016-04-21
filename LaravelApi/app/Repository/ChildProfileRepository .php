<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildProfile;
/**
 * Description of ParentService
**/
class ChildProfileRepository {   
    
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
    public  function setFirstname($fistname) {
         $this->firstname = $firstname;
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
    
    
    public function saveChildProfile() {
        echo "g";
             //Add Exception here
        
    }    
    
}