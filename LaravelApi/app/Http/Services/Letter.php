<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Letter
 *
 * @author MSI1332
 */
class Letter {
    
    private $letterId;
    private $title;
    private $content;
    private $associatedImages;
    
    public function __construct($letterId) {
        $this->setLetterId($letterId);
    }
    
    function getLetterId() {
        return $this->letterId;
    }

    function getTitle() {
        return $this->title;
    }

    function getContent() {
        return $this->content;
    }

    function setLetterId($letterId) {
        $this->letterId = $letterId;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setContent($content) {
        $this->content = $content;
    }

    function getAssociatedImages() {
        return $this->associatedImages;
    }

    function setAssociatedImages(array $associatedImages) {
        $this->associatedImages = $associatedImages;
    }

    function createLetter(){}
    
    function editLatter(){}
    
    function deleteLetter(){}
    
    function getLetter(){
        return $this;
    }
}
