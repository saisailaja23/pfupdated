<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChildProfile;
use App\Models\Countries;

/**
 * Description of ParentService
**/
class ChildProfileRepository {   
    
	private $childId; 
    private $accountId;
   
    public function __construct($childId) {
         $this->setChildId($childId);
    }
	
    public  function setChildId($childId) {
 +       $this->childId = $childId;
      }
    public  function setFirstname($firstname) {
         $this->firstname = $firstname;
    } 

    public  function setLastname($lastname) {
         $this->lastname = $lastname;
    } 
    public  function setDOB($dob) {
         $this->dob= $dob;
    } 

     public  function setAbout($about) {
         $this->about= $about;
    } 
    public  function setGender($gender) {
         $this->gender= $gender;
    } 
    public  function setLocationId($locationId) {
         $this->locationId= $locationId;
    } 
    public  function setAgencyId($agencyId) {
         $this->agencyId= $agencyId;
    } 
    public  function setSibling_group($sibling_group) {
         $this->sibling_group= $sibling_group;
    } 
  
    /* Save Child details for CF*/
    
    
    public function saveChildProfile() {
        try{
       $childObj=new ChildProfile;
       $status=$childObj->insert(array('first_name'=>$this->firstname,
                                        'last_name'=>$this->lastname,
                                        'dob'=>$this->dob,
                                        'about'=>$this->about,
                                        'gender'=>$this->gender,
                                        'location_id'=>$this->locationId,
                                        'agency_id'=>$this->agencyId,
                                        'is_sibling_group'=>$this->sibling_group
                                         ) );
         return $status;
        }
           catch(\Exception $e){
             //Add Exception here
           } 
    }   

    public function updateChildProfile($id) { 
        try{
        $childObj=new ChildProfile;
        $status=$childObj->where('child_id', $id)
                               ->update(['first_name'=>$this->firstname,
                                         
                                            'gender'=>$this->gender,
                                            'dob'=>$this->dob,
                                            'last_name'=>$this->lastname,
                                            'about'=>$this->about,
                                            'location_id'=>$this->locationId,
                                            'is_sibling_group'=>$this->sibling_group,
                                            'agency_id'=>$this->agencyId
                                            
                                            ]
                                            );
         return $status;
       }catch(\Exception $e){
             //Add Exception here

           } 

    }  

    public function getChildId(){
         try{
       $childObj=new ChildProfile;
       $childid = $childObj
                  ->where('child_id', '=', $this->childId)
                  ->get();
         return $childid;
        }catch(\Exception $e){
             //Add Exception here
        } 

    } 
    

    

    public function getAllChildids(){
         try{
           
       $childObj=new ChildProfile;
       $childids =$childObj
                  ->get();
         return $childids;
        }
           catch(\Exception $e){
             //Add Exception here
           } 

    }  

    public function getchildDetails(){
         try{
       $childObj=new ChildProfile;
        $childids =$childObj
          ->join('countries', 'countries.country_id', '=', '.child_profile.location_id')
                  ->where('child_id', '=', $this->childId)
                  ->first();
         return $childids;
        }
           catch(\Exception $e){
             //Add Exception here
           } 
    }
    
    
}