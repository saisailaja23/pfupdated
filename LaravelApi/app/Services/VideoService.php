<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Profiles;
use App\Models\Ethnicity;
use App\Models\Albums;
use App\Models\Video;
use App\Models\BxPhotosMain;
use App\Models\SysAlbumsObjects;
use App\Repository\ProfileRepository;
use App\Repository\AlbumsRepository;
use App\Repository\VideoRepository;
use App\Exceptions\ParentFinderException;
/**
 * Description of AccountService
**/
class VideoService {

    private $VideoId; 
    private $account_id;
    private $Uri;
    private $YoutubeLink;
    private $Source;
        
    public function __construct($VideoId) {
       $this->setVideoId($VideoId);      
    }
    
    public  function getVideoId() {
       return $this->VideoId;
    }

    public  function setVideoId($VideoId) {
         $this->VideoId = $VideoId;
    } 

    public  function getVideoYoutubeLink() { 
       return $this->VideoYoutubeLink;
    }
    public  function getVideoSource() {
       return $this->VideoSource;
    }
    public  function getVideoUri() {
       return $this->VideoUri;
    }
  
  
   
    public function getAlbum() {
        try{ 
        $videoObj=new VideoRepository($this->VideoId);
        if($videoDetails=$videoObj->getVideoDetails()){
            $this->VideoYoutubeLink=$videoDetails->YoutubeLink;
            $this->VideoSource=$videoDetails->Source;
            $this->VideoUri=$videoDetails->Uri;
            $this->Id=$videoDetails->ID;
            return $this;
        }
    else{
         throw new ParentFinderException('album_not_found');
    }
    }
    catch(\Exception $e){
            throw new ParentFinderException('album_not_found');
        } 
         
    }

    public function getAlbumByID($account_id){  
        try{
        $album=new AlbumsRepository(null);  
        $this->albumId =$album->getAlbumID($account_id);
        return $this;
    }
    catch(\Exception $e){
             //catch Exception here
        } 
    }
    
}