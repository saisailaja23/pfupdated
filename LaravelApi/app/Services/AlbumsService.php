<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\Albums;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;
use App\Repository\ProfileRepository;
use App\Repository\AlbumsRepository;
/**
 * Description of AccountService
**/
class AlbumsService {

    private $ID; 
    private $account_id;
    private $Ext;
    private $Title;
    private $Hash;
        
    public function __construct($ID) {
       $this->setAlbumId($ID);      
    }
    
    public  function getAlbumId() {
       return $this->ID;
    }

    public  function setAlbumId($ID) {
         $this->AlbumId = $ID;
    } 

    public  function getAlbumExt() {
       return $this->AlbumExt;
    }
    public  function getAlbumTitle() {
       return $this->AlbumTitle;
    }
    public  function getAlbumHash() {
       return $this->AlbumHash;
    }
  
   
    public function getAlbum() {
        $albumObj=new AlbumsRepository($this->ID);
        $albumDetails=$albumObj->getAlbumDetails();
        $this->journalCaption=$journalDetails->PostCaption;
        $this->journalPhoto=$journalDetails->PostPhoto;
        $this->journalUri=$journalDetails->PostUri;
        return $this;
         
    }

    public function getAlbumID($account_id){  
        $album=new AlbumsRepository(null);  
        $this->albumId =$album->getAlbumID($account_id);
        return $this;
    }
    
}