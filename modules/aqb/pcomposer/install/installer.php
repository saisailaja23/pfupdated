<?
/***************************************************************************
* 
*     copyright            : (C) 2009 AQB Soft
*     website              : http://www.aqbsoft.com
*      
* IMPORTANT: This is a commercial product made by AQB Soft. It cannot be modified for other than personal usage.
* The "personal usage" means the product can be installed and set up for ONE domain name ONLY. 
* To be able to use this product for another domain names you have to order another copy of this product (license).
* 
* This product cannot be redistributed for free or a fee without written permission from AQB Soft.
* 
* This notice may not be removed from the source code.
* 
***************************************************************************/

require_once(BX_DIRECTORY_PATH_CLASSES . "BxDolInstaller.php");

class AqbPCInstaller extends BxDolInstaller {
    function AqbPCInstaller($aConfig) {
        parent::BxDolInstaller($aConfig);
    }
	
	function install($aParams){
	    $aResult = parent::install($aParams);
	    if($aResult['result'] && BxDolRequest::serviceExists('aqb_pcomposer', 'create_first')) BxDolService::call('aqb_pcomposer', 'create_first');
	    return $aResult;
	}
	
	function uninstall($aParams) {
		BxDolService::call('aqb_pcomposer', 'uninstall_integration');
		return parent::uninstall($aParams);
	}
}
?>