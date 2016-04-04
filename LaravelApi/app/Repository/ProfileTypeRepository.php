<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\Profiletype;
use App\Exceptions\ParentFinderException;

/**
 * Description of ParentService
**/
class ProfileTypeRepository {

   
    private $id;

    public function __construct($id) {
       $this->setId($id);

    }

     public  function getId() {
       return $this->id;
    }
    public  function setId($id) {
         $this->id = $id;
    }   
    

   public function getProfileTypes(){
       try{
            $profiletypeobj=new Profiletype;
            $profiletypes =$profiletypeobj
                                ->get();
           //print_r($profiletypes);
            return $profiletypes;
        }catch(\Exception $e){
            //Add Exception here
        }  

   }
   
 
   
   
}



