<?
/***************************************************************************
Affiliates Pro v.1.0
Created by Martinboi
http://www.harvest-media.com
http://www.dolphintuts.com
***************************************************************************/
bx_import('BxDolAlerts');
require_once('HmAffProHandler.php');

class HmAffProAlertsResponse extends BxDolAlertsResponse {
	
	function HmAffProAlertsResponse() {
	    parent::BxDolAlertsResponse();
		$this->_oHandleMember = new HmAffProHandler();
	}

    function response($oAlert) {

		if($oAlert->sUnit == 'affiliates' && $oAlert->sAction == 'approve')
			$this ->_oHandleMember->approveAffiliate($oAlert);

		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'join')
			$this ->_oHandleMember->handleJoin($oAlert);

		if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'set_membership')
			$this ->_oHandleMember->handleSetMembership($oAlert);
    }

}
?>