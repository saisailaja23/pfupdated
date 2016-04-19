<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use App\Repository\LetterRepository;
use App\Exceptions\ParentFinderException;
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
    

    /*Get Letter*/
    function getLetter(){
        try{
        $letterObj=new LetterRepository($this->letterId);
        if($letterDetails=$letterObj->getLetters()){
        $this->title=$letterDetails->label;
        $this->content=$letterDetails->description;
        $this->associatedImage=$letterDetails->img;
        return $this;
    }
    else{
        throw new ParentFinderException('letter_not_found');
    }
    }
    catch(\Exception $e){
            throw new ParentFinderException('letter_not_found');
        } 
    }

    public function saveletterDetails($data){       
    
        $letter=new LetterRepository(null);
        $letter->setLabel($data['label']);
         $letter->setDescription($data['description']);
          $letter->setAccountid($data['account_id']);
           $letter->setSlug($data['slug']);
              $letter->setImage($data['image']);
                 $letter->setIsdefault($data['default']);
                    $letter->setSortorder($data['sortorder']);
        $insertStatus=$letter->insertLetter();
    }
    
}


    
