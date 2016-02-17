<?
/***************************************************************************
Dolphin Subscriptions v.2.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolAlerts');
require_once('HmSubsHandler.php');

class HmSubsAlertsResponse extends BxDolAlertsResponse {
	
	function HmSubsAlertsResponse() {
	    parent::BxDolAlertsResponse();
		$this -> _oHandleMember = new HmSubsHandler();
	}

    function response($oAlert) {
    	$sMethodName = '_process' . ucfirst($oAlert->sUnit) . str_replace(' ', '', ucwords(str_replace('_', ' ', $oAlert->sAction)));
    	if(method_exists($this, $sMethodName))
            $this->$sMethodName($oAlert);

		if($oAlert->sUnit == 'subs' && $oAlert->sAction == 'cur_page')
			$this ->_oHandleMember ->currentPage($oAlert);

		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'login')
			$this ->_oHandleMember->handleLogin($oAlert);

		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'join')
			$this ->_oHandleMember->handleJoin($oAlert);
    }

}
?>