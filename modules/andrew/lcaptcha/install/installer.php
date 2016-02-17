<?php
/***************************************************************************
*
* IMPORTANT: This is a commercial product made by AndrewP and cannot be modified for other than personal usage. 
* This product cannot be redistributed for free or a fee without written permission from AndrewP. 
* This notice may not be removed from the source code.
*
***************************************************************************/
bx_import('BxDolInstaller');

class ALCaptchaInstaller extends BxDolInstaller {
    function ALCaptchaInstaller($aConfig) {
        parent::BxDolInstaller($aConfig);
    }

    function install($aParams) {
        $aResult = parent::install($aParams);

        updateStringInLanguage('_FieldDesc_Captcha_Join', 'Try to answer this question');
        updateStringInLanguage('_Captcha check failed', 'Your answer is not logical');
        compileLanguage();

        return $aResult;
    }

    function uninstall($aParams) {
        updateStringInLanguage('_FieldDesc_Captcha_Join', 'Let us check that you are not a robot. Just enter the text you see on the picture.');
        updateStringInLanguage('_Captcha check failed', 'Security image check failed');
        compileLanguage();

        return parent::uninstall($aParams);
    }
}
