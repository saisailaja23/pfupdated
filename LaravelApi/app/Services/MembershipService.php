<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\MembershipRepository;
/**
 * Description of ParentService
**/

class MembershipService{

    private $id; 
    private $name;
    private $icon;
        
    public function __construct($id) {
       $this->setId($id);      
    }
    
    public  function getId() {
       return $this->id;
    }

    public  function setId($id) {
         $this->id = $id;
    } 

    public  function getname() {
       return $this->name;
    }
    public  function geticon() {
       return $this->icon;
    }
    
    
  
   
    


   public function getMembershipDetails($account_id){
         try{
        $membershipobj=new MembershipRepository(null);
        $membershipDetails=$membershipobj->getMembershipDetails();
                   $this->name=$membershipDetails->id;
                   $this->icon=$membershipDetails->title;
                   $this->id=$membershipDetails->uri;

           // print_r($this);
        return $this;   
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    
    }

    
    
}


    
