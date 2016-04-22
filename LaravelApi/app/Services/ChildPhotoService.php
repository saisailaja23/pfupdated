<?php
namespace App\Services;
use App\Repository\ChildPhotoRepository;
use App\Repository\EthnicityRepository;

class ChildPhotoService {

    private $childId; 
   
        
    public function __construct($photoId) {
       $this->setPhotoId($photoId);      
    }
    
    
    public  function setPhotoId($photoId) {
         $this->photoId = $photoId;
    } 

    


    /* Save Child  Details */
    
    public function saveChildPhoto($data){
        echo "s";
        $photo=new ChildPhotoRepository(null);
        $photo->setTitle($data['title']);
        $photo->setUrl($data['url']);
        $photo->savePhoto();
       
    }   
    
    
}


    
