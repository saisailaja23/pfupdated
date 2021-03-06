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

bx_import('BxDolModule');
bx_import('BxDolEmailTemplates');

require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );
//sandbox's pass 302162261
class AqbMembershipVouchersModule extends BxDolModule {
	/**
	 * Constructor
	 */
	function AqbMembershipVouchersModule($aModule) {
	    parent::BxDolModule($aModule);
	}

	function getVouchers() {
		$aMemberships = $this->_oDb->getMembershipsDetails();
		if (empty($aMemberships)) return MsgBox(_t('_Empty'));

		$aCodes = $this->_oDb->getCodesArray();

		return $this->_oTemplate->getMembershipsCodes($aMemberships, $aCodes);
	}
	function actionEdit($iCodeID = 0) {
		if (!isAdmin()) return MsgBox('Permission denied');

		if (isset($_REQUEST['id'])) {
			$_REQUEST['save'] = $_POST['save'] = $_GET['save'] = 'save';
			$iCodeID = $_REQUEST['id'];
		}
		$iCodeID = intval($iCodeID);
		$aCodeDetails = array();
		if ($iCodeID) $aCodeDetails = $this->_oDb->getCodeDetails($iCodeID);
		else $aCodeDetails['PriceID'] = intval($_REQUEST['price_id']);
		$oForm = $this->_oTemplate->getEditForm($aCodeDetails);

		$sResult = '';
		if ($oForm->isSubmittedAndValid()) {
			$aCode = array(
				'ID' => $iCodeID,
				'PriceID' => $aCodeDetails['PriceID'],
				'Code' => process_db_input(trim($_REQUEST['code'])),
				'Discount' => intval($_REQUEST['discount']),
				'SingleUse' => $_REQUEST['singleuse'] ? 1 : 0,
				'Starts' => strtotime($_REQUEST['starts']),
				'Ends' => strtotime($_REQUEST['ends']),
				'Threshold' => intval($_REQUEST['threshold']),
			);
			$this->_oDb->saveCode($aCode);

			$sResult = MsgBox($iCodeID ? _t('_aqb_membership_saved') : _t('_aqb_membership_added'), 2);
			$sResult .= '<script language="javascript">setTimeout("window.location.reload();", 2000)</script>';
		} else {
			$sResult = '<div style="position: relative;">'.$oForm->getCode().LoadingBox('formItemEditLoading').'</div>';
		}

		return PopupBox('aqb_popup', $iCodeID ? _t('_aqb_membership_edit') : _t('_aqb_membership_add'), $sResult);
	}
	function actionDelete($iCodeID) {
		if (!isAdmin()) return MsgBox('Permission denied');
		$iCodeID = intval($iCodeID);
		$this->_oDb->deleteCode($iCodeID);
	}
	function serviceGetBlockCodeAvailable() {
		if(!$this->isLogged())
            return MsgBox(_t('_membership_err_required_login'));

        $aMembership = $this->_oConfig->_oMembershipModuleInstance->_oDb->getMembershipsBy(array('type' => 'price_all'));
        if(empty($aMembership))
            return MsgBox(_t('_membership_txt_empty'));

        $aDiscount = null;
		if ($_POST['code']) {
			$sCode = trim($_POST['code']);
			$iPricingID = intval($_POST['price_id']);
			$iDiscount = $this->_oDb->getCodeDiscount($iPricingID, $sCode);
			if (!$iDiscount) {
				echo MsgBox(_t('_aqb_membership_vouchers_wrong_code'), 5);
			} else {
				echo MsgBox(_t('_aqb_membership_vouchers_right_code'), 5);
				$aDiscount[$iPricingID] = array('value' => $iDiscount, 'code' => $sCode);
			}
		}elseif ($_POST['take']) {
			$iPricingID = intval($_POST['take']);
			$sCode = $_POST['voucher_code'];
			if (!$sCode) echo MsgBox(_t('_aqb_membership_vouchers_wrong_code'), 5);
			else {

                                //Modified by joseph to process the membership upgrade if the price is zero start
                                foreach($aMembership as $aMembershipPrice)
                                {
                                    if($aMembershipPrice['price_id'] == $iPricingID  && $aMembershipPrice['price_amount'] == 0 )
                                    {
                                        $iDiscount=100;
                                        break;
                                    }
                                }
                                if($iDiscount!=100)
				$iDiscount = $this->_oDb->getCodeDiscount($iPricingID, $sCode);
                                //Modified by joseph to process the membership upgrade if the price is zero end
                                
				if ($iDiscount != 100) echo MsgBox(_t('_aqb_membership_vouchers_wrong_code'), 5); {
					buyMembership(getLoggedId(), $iPricingID, $aCode[$iPricingID]);
					$aDiscount = null;
					$iNewItem = $this->_oDb->createCartItem(getLoggedId(), $iPricingID, $iDiscount, $sCode);
					$this->_oDb->processItem($iNewItem);
                                        //joseph added below two line to redirect the page to success page
                                        $aResult['st']='UpgradedMembership';
                                         Redirect(BX_DOL_URL_ROOT . 'page/paymentresult', $aResult, 'get', 'Payment Result');
                                         exit();
                                         // Joseph commented the below line to redirect to a successfull page
					//echo MsgBox(_t('_aqb_membership_vouchers_membership_assigned'), 2).'<script language="javascript">setTimeout("window.location.href = window.location.href;", 2000);</script>';
					//return;
				}
			}
		}

        return $this->_oTemplate->displayAvailableLevels($aMembership, $aDiscount);
	}

	function serviceResponseProfileDelete($oAlert) {
		$iProfileId = intval($oAlert->iObject);
		if (!$iProfileId) return false;
		$this->_oDb->cleanTransactions($iProfileId);
        return true;
	}

	function actionAddToCart($iPrice, $sCode) {
		$oJson = new Services_JSON();

		$iProfile = getLoggedId();
		$iPrice = intval($iPrice);
		$sCode = process_db_input(trim($sCode));

		$aResult = array();

		if (!isLogged()) die;

		if (!$this->_oDb->isPricingOptionExists($iPrice)) {
			return $oJson->encode(array('message' => _t('_aqb_membership_vouchers_no_pricing_option')));
		}

		$iDiscount = $this->_oDb->getCodeDiscount($iPrice, $sCode);
		if (!$iDiscount) {
			return $oJson->encode(array('message' => _t('_aqb_membership_vouchers_wrong_code')));
		}

		$iNewCartItem = $this->_oDb->createCartItem($iProfile, $iPrice, $iDiscount, $sCode);
		$aPaymentResult = BxDolService::call('payment', 'add_to_cart', array(0, $this->_oConfig->getId(), $iNewCartItem, 1));
		if ($aPaymentResult['code'] != 0) {
			return $oJson->encode(array('message' => $aPaymentResult['message']));
		}

		$aResult['message'] = $aPaymentResult['message'];
		bx_import('BxDolPermalinks');
		$o = new BxDolPermalinks();
		$aResult['redirectTo'] = BX_DOL_URL_ROOT.$o->permalink('modules/?r=payment/').'cart/';
        return $oJson->encode($aResult);
	}


	/*--- Integration with Payment module ---*/
	/**
	 * Is used in Orders Administration to get all products of the requested seller(vendor).
	 *
	 * @param integer $iVendorId seller ID.
	 * @return array of products.
	 */
	function serviceGetItems($iVendorId) {
	    return BxDolService::call('membership', 'get_items', array($iVendorId));
	}
	/**
	 * Is used in Shopping Cart to get one product by specified id.
	 *
	 * @param integer $iClientId client's ID.
	 * @param integer $iItemId product's ID.
	 * @return array with product description.
	 */
	function serviceGetCartItem($iClientId, $iItemId) {
	    return $this->_getCartItem($iClientId, $iItemId);
	}
	/**
	 * Register purchased product.
	 *
	 * @param integer $iClientId client's ID.
	 * @param integer $iSellerId seller's ID.
	 * @param integer $iItemId product's ID.
	 * @param integer $iItemCount product count purchased at the same time.
	 * @param string $sOrderId internal order ID generated for the payment.
	 * @return array with product description.
	 */
	function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrderId) {
		$aItemId = $this->_oDb->getInternalItem($iItemId, $iClientId);
		$iPriceItemId = $aItemId['PriceID'];

		//AQB Affiliate\Referrals
		$iPrice = $this->_oDb->getOne("SELECT `Price` FROM `sys_acl_level_prices` WHERE `id` = {$iPriceItemId} LIMIT 1");
		$iPrice = ceil($iPrice * (100 - $aItemId['Discount'])) / 100;
	   	bx_import('BxDolAlerts');
	   	$oZ = new BxDolAlerts('aqb', 'upgrade', $iClientId, $this->_oConfig->_oMembershipModuleInstance->_oConfig->getId(), array('price' => $iPrice, 'number' => $iItemCount, 'membership' => $iPriceItemId));
	   	$oZ->alert();
	  	//AQB Affiliate\Referrals

	    for($i=0; $i<$iItemCount; $i++) {
            buyMembership($iClientId, $iPriceItemId, $sOrderId);
	    }
	    $this->_oDb->processItem($iItemId);

	    return $this->_getCartItem($iClientId, $iItemId);
	}
	/**
	 * Unregister the product purchased earlier.
	 *
	 * @param integer $iClientId client's ID.
	 * @param integer $iSellerId seller's ID.
	 * @param integer $iItemId product's ID.
	 * @param integer $iItemCount product count.
	 * @param string $sOrderId internal order ID.
	 */
	function _getCartItem($iClientId, $iItemId){
		$aItemId = $this->_oDb->getInternalItem($iItemId, $iClientId);
		$iPriceItemId = $aItemId['PriceID'];
	    $aItem = $this->_oConfig->_oMembershipModuleInstance->_oDb->getMembershipsBy(array('type' => 'price_id', 'id' => $iPriceItemId));

	    if(empty($aItem) || !is_array($aItem))
	       return array();

	    return array(
	       'id' => $iItemId,
	       'title' => $aItem['mem_name'] . ' ' . _t('_membership_txt_on_N_days', $aItem['price_days']).'<br />'._t('_aqb_membership_vouchers_transaction_info', $aItemId['Discount'], $aItemId['Code']),
	       'description' => $aItem['mem_description'],
	       'url' => BX_DOL_URL_ROOT . $this->_oConfig->_oMembershipModuleInstance->_oConfig->getBaseUri() . 'index',
	       'price' => ceil($aItem['price_amount'] * (100 - $aItemId['Discount'])) / 100
	    );
	}
}
?>