<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

/**
 * Description of Parentprofile
 *
 * @author MSI1332
 */
class ParentProfile {
    
    private $profileId;
    private $firstName;
    private $lastName;
    private $dob;
    private $faith;
    private $faithId;
    private $ethnicity;
    private $ethnicityId;
    private $religion;
    private $religionId;  
    private $waitingId;
    private $waiting;  
    private $gender;
    private $generId;
    private $accountId;
    
    function __construct($profileId) {
        $this->profileId = $profileId;
    }

    
    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getDob() {
        return $this->dob;
    }

    function getFaith() {
        return $this->faith;
    }

    function getFaithId() {
        return $this->faithId;
    }

    function getEthnicity() {
        return $this->ethnicity;
    }

    function getEthnicityId() {
        return $this->ethnicityId;
    }

    function getReligion() {
        return $this->religion;
    }

    function getReligionId() {
        return $this->religionId;
    }

    function getWaitingId() {
        return $this->waitingId;
    }

    function getWaiting() {
        return $this->waiting;
    }

    function getGender() {
        return $this->gender;
    }

    function getAccountId() {
        return $this->accountId;
    }

    function setDob($dob) {
        $this->dob = $dob;
    }

    function setFaithId($faithId) {
        $this->faithId = $faithId;
    }

    function setEthnicityId($ethnicityId) {
        $this->ethnicityId = $ethnicityId;
    }

    function setReligionId($religionId) {
        $this->religionId = $religionId;
    }

    function setWaitingId($waitingId) {
        $this->waitingId = $waitingId;
    }

    function setAccountId($accountId) {
        $this->accountId = $accountId;
    }

    function setGenerId($generId) {
        $this->generId = $generId;
    }

    function getProfile(){
        
    }
    
    function saveprofile(){
        
    }
 
    
    private function editprofile(){
        
    }
}
