<?php
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by AndrewP and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from AndrewP. 
* This notice may not be removed from the source code.
*
***************************************************************************/
bx_import('BxDolModuleDb');

class ALCaptchaDb extends BxDolModuleDb {
    var $_oConfig;

    // constructor
    function ALCaptchaDb(&$oConfig) {
        parent::BxDolModuleDb($oConfig);
        $this->_oConfig = $oConfig;
    }
    function getQuestions() {
        return $this->getAll('SELECT * FROM `alcaptcha_questions` ORDER BY `id` ASC');
    }
    function getQuestionInfo($i) {
        $aRes = $this->getAll("SELECT * FROM `alcaptcha_questions` WHERE `id` = '{$i}'");
        return $aRes[0];
    }
    function deleteQuestion($i) {
        return $this->res("DELETE FROM `alcaptcha_questions` WHERE `id` = '{$i}' LIMIT 1");
    }
}
