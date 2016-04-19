<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Regions;

/**
 * Description of ParentService
**/
class RegionRepository {

   
    private $regionId;

    public function __construct($regionId) {
       $this->setRegionId($regionId);

    }
    
    public  function getRegionId() {
       return $this->regionId;
    }

    public  function setRegionId($regionId) {
         $this->regionId = $regionId;
    }

  
  
    /* Get Religion */
    public function getRegionDetails() {
        try{
            $regionObj=new Regions;
            $regionDetails =$regionObj->where('RegionId', '=',$this->regionId)->first();
            return $regionDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }

     public function getRegionById($region) {
        try{
            $regionObj=new Regions;
            $regionDetails =$regionObj->where('Region', '=',$region)->first();
            return $regionDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }

    public function getRegionByStateId() {
        try{
            $regionObj=new Regions;
            $regionDetails =$regionObj->get();
            return $regionDetails;
        }catch(\Exception $e){
             //Add Exception here
        }  
    }


        
    
}
