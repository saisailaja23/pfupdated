<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

/**
 * Description of CoupleProfile
 *
 * @author MSI1332
 */
class CoupleProfile {
    
    private $accountId;
    private $parentprofile1;
    private $parentprofile2;
    private $letters;
    private $journals;
    private $photoAlbums;
    private $videoAlbums;
    private $childPreference;
    private $avatar;
    
    
    function __construct($accountId) {
        $this->accountId = $accountId;
    }
    
    function getAccountId() {
        return $this->accountId;
    }

    function getParentprofile1() {
        $profileId = $this->getProfileId(true);
        $parentProfile = new ParentProfile($profileId);
        $this->parentprofile1 = $parentProfile->getProfile();
        return $this->parentprofile1;
    }

    function getParentprofile2() {
        $profileId = $this->getProfileId(false);
        $parentProfile = new ParentProfile($profileId);
        $this->parentprofile2 = $parentProfile->getProfile();
        return $this->parentprofile2;
    }

    function getLetters() {
        
        $letterIds = $this->getLetterIds();
        $letters[];
        foreach ($letterIds as $id) {
           $letterObj = new Letter();
           $letter = $letterObj->getLetter();
           array_push($letters, $letter);
        }
        $this->letters = $letters;
        return $this->letters;
    }

    function getJournals() {
        return $this->journals;
    }

    function getPhotoAlbums() {
        return $this->photoAlbums;
    }

    function getVideoAlbums() {
        return $this->videoAlbums;
    }

    function getChildPreference() {
        return $this->childPreference;
    }

    function getAvatar() {
        return $this->avatar;
    }

    function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    function getProfileName(){
        
    }
    /**
     * 
     * @param boolean $isFirstParent
     * @return Integer $profileId
     */
    private function getProfileId($isFirstParent){
        return $profileId;
    }
    /**
     * 
     * @return array $ids
     */
    private function getLetterIds(){
        $ids[];
        return $ids;
    }
}
