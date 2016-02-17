<?php
/***************************************************************************
*
*     copyright            : (C) 2011 AQB Soft
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

bx_import('BxDolModuleTemplate');
bx_import('BxTemplFormView');

define('SECONDS_IN_DAY', 24*3600);

class AqbCodeFormView extends BxTemplFormView {
	var $_oDb;

	function AqbCodeFormView($aParams, $oDb) {
		$this->_oDb = $oDb;
		return parent::BxTemplFormView($aParams);
	}
	function isValid() {
		$bRet = parent::isValid();

		if (!$_REQUEST['id']) {
			$sCode = trim($_REQUEST['code']);
			if ($this->_oDb->isCodeExists($sCode)) {
				$this->aInputs['code']['error'] = _t('_aqb_membership_code_err2');
				$bRet = false;
			}
		}
		$iStarts = strtotime($_REQUEST['starts']);
		$iEnds = strtotime($_REQUEST['ends']);

		if ($iEnds + SECONDS_IN_DAY < time()) {
			$this->aInputs['ends']['error'] = _t('_aqb_membership_ends_err1');
			$bRet = false;
		}
		if ($iEnds < $iStarts) {
			$this->aInputs['starts']['error'] = _t('_aqb_membership_ends_err2');
			$this->aInputs['ends']['error'] = _t('_aqb_membership_ends_err2');
			$bRet = false;
		}

		return $bRet;
	}
}

class AqbMembershipVouchersTemplate extends BxDolModuleTemplate {
	/**
	 * Constructor
	 */
	function AqbMembershipVouchersTemplate(&$oConfig, &$oDb) {
	    parent::BxDolModuleTemplate($oConfig, $oDb);
	}

	function getMembershipsCodes($aMemberships, $aCodes) {
		$aWrapperForm = array(
	        'params' => array(
	        	'remove_form' => true
	        ),
	        'inputs' => array ()
		);

		$sCurrencyCode = $this->_oConfig->getCurrencySign();

		foreach ($aMemberships as $iIndex => $aMembership) {
			if ($aMembership['price_days'] == 0) $aMembership['price_days'] = 'lifetime';
			else $aMembership['price_days'] .= _t('_days');

			$sCodesList = '';
			$iActive = 0;
			$iExpired = 0;
			$iPending = 0;
			list($sCodesList, $iActive, $iExpired, $iPending) = $this->getCodesList($aCodes[$aMembership['price_id']], $aMembership['price_id']);



			$sCodesStats = '('._t('_aqb_membership_active', $iActive);
			if ($iExpired) $sCodesStats .= ', '._t('_aqb_membership_expired', $iExpired);
			if ($iPending) $sCodesStats .= ', '._t('_aqb_membership_pending', $iPending);
			$sCodesStats .= ')';

			$aWrapperForm['inputs']['header'.$iIndex] = array(
                'type' => 'block_header',
                'caption' => "<strong>{$aMembership['mem_name']}</strong> {$aMembership['price_days']} "._t('_aqb_membership_vouchers_for').' '.$sCurrencyCode."{$aMembership['price_amount']} ".$sCodesStats,
				'collapsable' => true,
				'collapsed' => true
            );

            $aWrapperForm['inputs']['vouchers'.$iIndex] = array(
            	'type' => 'custom',
            	'content' => '<div style="width:100%">'.$sCodesList.'</div>',
            	'colspan' => true,
            	'attrs_wrapper' => array(
            		'style' => 'float: none; width: 100%;'
            	)
            );

            $aWrapperForm['inputs']['header'.$iIndex.'_end'] = array(
                'type' => 'block_end'
            );
		}

		$this->addAdminCss('admin.css');
		$this->addAdminJs('admin.js');
		$this->addAdminJs('jquery.ui.datepicker.min.js');
		$this->addJsTranslation('_aqb_membership_sure');

		$oForm = new BxTemplFormView($aWrapperForm);
		return $oForm->getCode().$this->getJsObject();
	}

	function getCodesList($aCodes, $iPriceID) {
		$iActive = 0;
		$iExpired = 0;
		$iPending = 0;
		$sRet .= '<table class="aqb_codes_options" cellpadding="5" width="100%">
					<tr class="aqb_header_row">
						<td>'._t('_aqb_membership_code').'</td>
						<td>'._t('_aqb_membership_discount').'</td>
						<td>'._t('_aqb_membership_starts').'</td>
						<td>'._t('_aqb_membership_ends').'</td>
						<td>'._t('_aqb_membership_vouchers_used').'</td>
						<td>'._t('_aqb_membership_vouchers_singleuse').'</td>
						<td width=\"27%\">&nbsp;</td>
					</tr>';
		if (!empty($aCodes)) {
			$iNow = time();
			foreach ($aCodes as $aCode) {
				if ($aCode['Discount'] == '100') $aCode['Discount'] = _t('_aqb_membership_discount_free');
				else $aCode['Discount'] .= '%';

				$sThreshold = '';
				if ($aCode['Threshold']) $sThreshold = '/'.$aCode['Threshold'];

				$sRowClass = '';
				if ($iNow > $aCode['Start'] && $iNow < $aCode['End'] + SECONDS_IN_DAY) {$iActive++; $sRowClass = 'aqb_row_active';}
				elseif ($aCode['End'] + SECONDS_IN_DAY < $iNow) {$iExpired++; $sRowClass = 'aqb_row_expired';}
				else {$iPending++; $sRowClass = 'aqb_row_pending';}

				$sRet .= "	<tr class=\"{$sRowClass}\">
								<td>{$aCode['Code']}</td>
								<td>{$aCode['Discount']}</td>
								<td>".getLocaleDate($aCode['Start'])."</td>
								<td>".getLocaleDate($aCode['End'])."</td>
								<td>"._t('_N times', $aCode['Used'].$sThreshold)."</td>
								<td>".($aCode['SingleUse'] ? _t('_Yes') : _t('_No'))."</td>
								<td>
									<input type=\"button\" value=\""._t('_aqb_membership_edit')."\" onclick=\"javascript: oAqbMembershipVouchers.showEditForm({$aCode['ID']}, {$aCode['PriceID']});\"/>
									<input type=\"button\" value=\""._t('_aqb_membership_delete')."\" onclick=\"javascript: oAqbMembershipVouchers.deleteCode({$aCode['ID']});\"/>
								</td>
							</tr>";
			}
		} else {
			$sRet .= '<tr><td colspan="7">'.MsgBox(_t('_Empty')).'</td></tr>';
		}


		$sRet .= "<tr><td colspan=\"7\" align=\"right\"><input type=\"button\" value=\""._t('_aqb_membership_add')."\" onclick=\"javascript: oAqbMembershipVouchers.showEditForm(0, {$iPriceID});\"/></td></tr>";
		$sRet .= '</table>';

		return array($sRet, $iActive, $iExpired, $iPending);
	}
	function getEditForm($aCodeDetails) {
		$sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();

		$aDiscountValues = array();
		for ($i = 1; $i < 100; $i++) $aDiscountValues[$i] = $i.'%';
		$aDiscountValues[100] = _t('_aqb_membership_discount_free');

		if (empty($aCodeDetails['Code'])) $aCodeDetails['Code'] = strtoupper(substr(md5(mt_rand().mt_rand().mt_rand().mt_rand().mt_rand().mt_rand()), 0, 8));

		$aForm = array(
	    	'form_attrs' => array(
                'id' => 'aqb_vouchers_frm',
                'name' => 'aqb_vouchers_frm',
                'action' => $sBaseUrl . 'admin/',
                'method' => 'post',
                'onsubmit' => 'javascript: return oAqbMembershipVouchers.submitEditForm(this)'
            ),
            'params' => array (
                'db' => array(
                    'submit_name' => 'save',
                ),
            ),
            'inputs' => array (
            	'id' => array(
            		'type' => 'hidden',
            		'name' => 'id',
            		'value' => intval($aCodeDetails['ID']),
            	),
            	'price_id' => array(
            		'type' => 'hidden',
            		'name' => 'price_id',
            		'value' => intval($aCodeDetails['PriceID']),
            	),
            	'code' => array(
            		'type' => 'text',
            		'name' => 'code',
            		'caption' => _t('_aqb_membership_code'),
            		'info' => _t('_aqb_membership_code_info'),
            		'value' => htmlspecialchars($aCodeDetails['Code']),
            		'checker' => array(
            			'func' => 'avail',
            			'error' => _t('_aqb_membership_code_err1'),
            		),
            		'required' => true,
            	),
            	'discount' => array(
            		'type' => 'select',
            		'name' => 'discount',
            		'caption' => _t('_aqb_membership_discount'),
            		'values' => $aDiscountValues,
            		'value' => $aCodeDetails['Discount'],
            	),
            	'starts' => array(
            		'type' => 'date',
            		'name' => 'starts',
            		'caption' => _t('_aqb_membership_starts'),
            		'value' => !empty($aCodeDetails['Start']) ? $aCodeDetails['Start'] : date('Y-m-d'),
            	),
            	'ends' => array(
            		'type' => 'date',
            		'name' => 'ends',
            		'caption' => _t('_aqb_membership_ends'),
            		'value' => !empty($aCodeDetails['End']) ? $aCodeDetails['End'] : date('Y-m-d', time() + 30*SECONDS_IN_DAY),
            	),
            	'singleuse' => array(
            		'type' => 'checkbox',
            		'name' => 'singleuse',
            		'caption' => _t('_aqb_membership_vouchers_singleuse'),
            		'info' => _t('_aqb_membership_vouchers_singleuse_info'),
            		'value' => 'on',
            		'checked' => $_REQUEST['save'] ? $_REQUEST['singleuse'] : $aCodeDetails['SingleUse']
            	),
            	'threshold' => array(
            		'type' => 'text',
            		'name' => 'threshold',
            		'caption' => _t('_aqb_membership_vouchers_threshold'),
            		'info' => _t('_aqb_membership_vouchers_threshold_info'),
            		'value' => $aCodeDetails['Threshold'] ? $aCodeDetails['Threshold'] : '',
            	),
                'submit' => array(
					'type' => 'submit',
					'name' => 'save',
					'value' => !empty($aCodeDetails['ID']) ? _t('_aqb_membership_vouchers_save') : _t('_aqb_membership_add'),
					'colspan' => true,
					'attrs_wrapper' => array(
                		'style' => 'margin-left: 300px;',
                	),
                )
			)
		);

		$oForm = new AqbCodeFormView($aForm, $this->_oDb);
		$oForm->initChecker($_POST);
		return $oForm;
	}


	function getJsObject() {
		$sBaseUrl = BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri();
		return '
			<script language="javascript">
			$(document).ready(function(){
				oAqbMembershipVouchers = new AqbMembershipVouchers({
					sBaseURL : "'.$sBaseUrl.'"
				});
			});
			</script>';
	}


	//almost exact copypaste from Membership Module
	function displayAvailableLevels($aValues, $aDiscount) {
	    $sCurrencyCode = strtolower($this->_oConfig->_oMembershipModuleInstance->_oConfig->getCurrencyCode());
	    $sCurrencySign = $this->_oConfig->_oMembershipModuleInstance->_oConfig->getCurrencySign();

	    $aMemberships = array();
	    foreach($aValues as $aValue) {
	    	$sButtons = '';

	    	if ($aDiscount[$aValue['price_id']]['value'] == 100) {
	    		$sButtons = '
	    			<button class="bx-btn bx-btn-img bx-btn-ifont" onclick="javascript:getFreeMembership('.$aValue['price_id'].',\''.$aDiscount[$aValue['price_id']]['code'].'\')">
                        '._t('_aqb_membership_vouchers_taking_it').'
                    </button>';
	    	} elseif ($aDiscount[$aValue['price_id']]['value']) {
	    		$sButtons = '
	    			<button class="bx-btn bx-btn-img bx-btn-ifont" onclick="javascript:AqbMembershipVouchersInitCartItem('.$aValue['price_id'].',\''.$aDiscount[$aValue['price_id']]['code'].'\');">
                        <i class="sys-icon shopping-cart"></i>
                        '._t('_payment_add_to_cart').'
                    </button>';
	    	} else {
	    		$sButtons = BxDolService::call('payment', 'get_add_to_cart_link', array(0, $this->_oConfig->_oMembershipModuleInstance->_oConfig->getId(), $aValue['price_id'], 1, 1));
	    	}

	    	if ($aDiscount[$aValue['price_id']]['value']) {
	    		 $aValue['price_amount'] = ceil($aValue['price_amount'] * (100 - $aDiscount[$aValue['price_id']]['value'])) / 100;
	    	}

            $aMemberships[] = array(
                'url_root' => BX_DOL_URL_ROOT,
                'id' => $aValue['mem_id'],
                'price_id' => $aValue['price_id'],
                'title' => $aValue['mem_name'],
                'icon' =>  $this->_oConfig->_oMembershipModuleInstance->_oConfig->getIconsUrl() . $aValue['mem_icon'],
                'description' => str_replace("\$", "&#36;", $aValue['mem_description']),
                'days' => $aValue['price_days'] > 0 ?  $aValue['price_days'] . ' ' . _t('_membership_txt_days') : _t('_membership_txt_expires_never') ,
                'price' => $aValue['price_amount'],
                'currency_code' => strtoupper($sCurrencyCode),
                'add_to_cart' => $sButtons
	        );
	    }

	    $this->addCss('levels.css');
	    $this->addJsTranslation('_aqb_membership_vouchers_code_required');
	    $sContent = $this->parseHtmlByName('available.html', array('bx_repeat:levels' => $aMemberships, 'actions_url' => BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri()));
	    return array($sContent, array(), array(), false);
	}
}
?>