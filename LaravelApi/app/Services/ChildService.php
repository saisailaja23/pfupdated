<?php
namespace App\Services;
use App\Repository\ChildProfileRepository;
use App\Repository\EthnicityRepository;

class ChildService {

    private $childId; 
   
        
    public function __construct($childId) {
       $this->setChildId($childId);      
    }
    
    
    public  function setChildId($childId) {
         $this->childId = $childId;
    } 

    


    /* Save Child  Details */
    public function saveChildDetails($data){
        try{
        $child=new ChildProfileRepository(null);
        $child->setFirstname($data['firstname']);
        $child->setLastname($data['lastname']);
        $child->setDOB($data['dob']);
        $child->setAbout($data['about']);
        $child->setGender($data['gender']);
        $child->setLocationId($data['location']);
        $child->setAgencyId($data['agency']);
        $child->setSibling_group($data['sibling_group']);
        $status=$child->saveChildProfile();
        return $status;
        }
        catch(\Exception $e){
             //Add Exception here
        } 
    } 
    
    
}


    
