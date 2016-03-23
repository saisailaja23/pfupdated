<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\LetterRepository;
/**
 * Description of ParentService
**/

class LetterService{

        
    private $letterId;
    private $title;
    private $content;
    private $associatedImage;
    
    public function __construct($letterId) {
        $this->setLetterId($letterId);
    }
    
    public function getLetterId() {
        return $this->letterId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getAssociatedImage() {
        return $this->associatedImage;
    }

    public function setLetterId($letterId) {
        $this->letterId = $letterId;
    } 
    
    function getLetter(){
        try{
        $letterObj=new LetterRepository($this->letterId);
        $letterDetails=$letterObj->getLetters();
        $this->title=$letterDetails->label;
        $this->content=$letterDetails->description;
        $this->associatedImage=$letterDetails->img;
        return $this;
    }
    catch(\Exception $e){
             //Add Exception here
        } 
    }

    
    
}


    
