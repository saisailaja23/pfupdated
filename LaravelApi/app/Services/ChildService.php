<?php
namespace App\Services;
use App\Repository\ChildProfileRepository;
use App\Repository\EthnicityRepository;
use App\Exceptions\ParentFinderException;


class ChildService {

    private $childId;
    private $first_name;
    private $last_name;
    private $dob;
    private $about;
    private $gender;
    private $is_sibling_group;
    private $status;
    private $is_private;
    private $location_id;
    private $agency_id; 
   
        
    public function __construct($childId) {
       $this->setChildId($childId);      
    }
    
    
    


    public  function getchildId() {
       return $this->childId;
    }
    public  function setChildId($childId) {
         $this->childId = $childId;
    } 

    public  function getfirst_name() {
       return $this->first_name;
    }

    public  function getlast_name() {
       return $this->last_name;
    }

    public  function getdob() {
       return $this->dob;
    }

    public  function getabout() {
       return $this->about;
    }

    public  function getgender() {
       return $this->gender;
    }

    public  function getis_sibling_group() {
       return $this->is_sibling_group;
    }

    public  function getis_private() {
       return $this->is_private;
    }

    public  function getstatus() {
       return $this->status;
    }

    public  function getlocation_id() {
       return $this->location_id;
    }
    public  function getCountry() {
       return $this->country;
    }

    public  function getagency_id() {
       return $this->agency_id;
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


    public function getChildDetails(){

         try{
        $child=new ChildProfileRepository($this->childId);
        $childDetails=$child->getchildDetails();
                    if(count($childDetails)>0){
                   $this->childId =  $childDetails->child_id;
                   $this->first_name=$childDetails->first_name;
                   $this->last_name=$childDetails->last_name;
                   $this->dob=$childDetails->dob;
                   $this->about=$childDetails->about;
                   $this->gender=$childDetails->gender;
                   $this->is_sibling_group=$childDetails->is_sibling_group;
                   $this->is_private=$childDetails->is_private;
                   $this->status=$childDetails->status;
                   $this->location_id=$childDetails->location_id;
                     $this->country=$childDetails->country;
                   
                   $this->agency_id=$childDetails->agency_id;
                  return $this; 
                  }
                  else{
                    throw new ParentFinderException('child_not_found');
                  }  
    }
    catch(\Exception $e){
             //Add Exception here
        }

    }

    public function updateChildDetails($data){
        try{
        $child=new ChildProfileRepository(null);
        $child->setChildId($data['child_id']);
        $child->setFirstname($data['firstname']);
        $child->setLastname($data['lastname']);
        $child->setDOB($data['dob']);
        $child->setAbout($data['about']);
        $child->setGender($data['gender']);
        $child->setLocationId($data['location']);
        $child->setAgencyId($data['agency']);
        $child->setSibling_group($data['sibling_group']);
        $status=$child->updateChildProfile($data['child_id']);
        return $status;
        }catch(\Exception $e){
             //Add Exception here
        }
    }   

    
    
}


    
