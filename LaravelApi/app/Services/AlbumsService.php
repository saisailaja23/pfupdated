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
use App\Exceptions\ParentFinderException;
/**
 * Description of AccountService
**/
class AlbumsService {

    private $AlbumId; 
    private $account_id;
    private $Ext;
    private $Title;
    private $Hash;
        
    public function __construct($AlbumId) {
       $this->setAlbumId($AlbumId);      
    }
    
    public  function getAlbumId() {
       return $this->AlbumId;
    }

    public  function setAlbumId($AlbumId) {
         $this->AlbumId = $AlbumId;
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
    public  function getAlbumUri() {
       return $this->AlbumUri;
    }
  
  
   
    public function getAlbum() {
        try{ 
        $albumObj=new AlbumsRepository($this->AlbumId);
        if($albumDetails=$albumObj->getAlbumDetails()){
        $this->AlbumExt=$albumDetails->Ext;
        $this->AlbumTitle=$albumDetails->Title;
        $this->AlbumHash=$albumDetails->Hash;
        $this->AlbumUri=$albumDetails->Uri;
        $this->Id=$albumDetails->ID;
        //print_r($this);
        return $this;
    }
    else{
        echo "s";
        throw new ParentFinderException('album_not_found');
        echo "a";
    }
    }
    catch(\Exception $e){
            throw new ParentFinderException('user_not_found');
        }          
    }

    public function getAlbumByID($account_id){  
        try{
        $album=new AlbumsRepository(null);  
        $this->albumId =$album->getAlbumID($account_id);
        return $this;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }
    
}