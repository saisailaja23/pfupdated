<?php
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by AndrewP and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from AndrewP. 
* This notice may not be removed from the source code.
*
***************************************************************************/
bx_import('BxDolCaptcha');

/**
 * Logical Captcha representation.
 * @see BxDolCaptcha
 */
class ALCaptcha extends BxDolCaptcha
{
    protected $_oTemplate;
    protected $_error = null;

    public function __construct($aObject, $oTemplate = null) {
        parent::__construct ($aObject);
        $this->_oTemplate = ($oTemplate) ? $oTemplate : $GLOBALS['oSysTemplate'];
    }
    public function display() {
        $sId = 'sys-captcha-' . time() . rand(0, PHP_INT_MAX);

        $aQuestions = $this->getLogicQuestions();
        $iCnt = count($aQuestions);
        $iRnd = rand(1, $iCnt);

        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();
        $mixedValue = $oSession->setValue('strLcap', $iRnd);

        return <<<EOC
<div class="input_wrapper input_wrapper_lcaptcha" id="{$sId}">
    <p>{$aQuestions[$iRnd]['q']}</p><input type="text" name="LCaptcha" class="form_input_lcaptcha" />
    <div class="input_close input_close_lcaptcha"></div>
</div>
EOC;
    }
    public function check() {
        bx_import('BxDolSession');
        $oSession = BxDolSession::getInstance();
        $iQuestion = (int)$oSession->getValue('strLcap');
        if ($iQuestion) {
            $aQuestions = $this->getLogicQuestions();
            if ($aQuestions[$iQuestion]) {
                $this->_error = 'WTF';
                return ($aQuestions[$iQuestion]['a'] == mb_strtolower($this->getUserResponse()));
            }
        }
        return false;
    }
    public function getUserResponse() { // strtolower
        return process_pass_data(bx_get('LCaptcha'));
    }
    public function isAvailable() {
        return true;
    }
    function getLogicQuestions() {
        $aRecords = $GLOBALS['MySQL']->getAll('SELECT * FROM `alcaptcha_questions` ORDER BY `id` ASC');
        $aRet = array();
        foreach ($aRecords as $i => $aVal) {
            $aRet[$aVal['id']] = array('q' => $aVal['question'], 'a' => $aVal['answer']);
        }
        return $aRet;
    }
}