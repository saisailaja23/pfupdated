<?php


namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ChildPhoto;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;

/**
 * Description of ParentService
**/
class ChildPhotoRepository {

   
  private $AlbumId;

    public function __construct($photoId) {
       $this->setphotoId($photoId);

    }

     public  function getAlbumId() {
       return $this->AlbumId;
    }
    public  function setPhotoId($photoId) {
         $this->photoId = $photoId;
    }      
    public  function setTitle($title) {
         $this->title= $title;
    } 
    public  function setUrl($url) {
         $this->url = $url;
    }      
         
    
    public function savePhoto(){
    echo "j";
      $childObj=new ChildPhoto;
       $status=$childObj->insert(array('title'=>$this->title,
                                        'url'=>$this->url,
                                        'status'=>$this->status,
                                        'child_id'=>$this->about
                                        
                                         ) );
    }
        
    }



