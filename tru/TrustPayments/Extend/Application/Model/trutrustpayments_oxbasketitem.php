<?php
/**
 * TrustPayments OXID
 *
 * This OXID module enables to process payments with TrustPayments (https://www.trustpayments.com//).
 *
 * @package Whitelabelshortcut\TrustPayments
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */require_once(OX_BASE_PATH . "modules/tru/TrustPayments/autoload.php");



/**
 * Class BasketItem.
 * Extends \oxbasketItem.
 *
 * @mixin \oxbasketItem
 */
class trutrustpayments_oxbasketitem extends trutrustpayments_oxbasketitem_parent {
	private static $blTruDisableCheckProduct = false;

	public function getArticle($blCheckProduct = false, $sProductId = null, $blDisableLazyLoading = false){
		return $this->_BasketItem_getArticle_parent(self::$blTruDisableCheckProduct ? false : $blCheckProduct, $sProductId, $blDisableLazyLoading);
	}

	protected function _BasketItem_getArticle_parent($blCheckProduct = false, $sProductId = null, $blDisableLazyLoading = false){
		return parent::getArticle($blCheckProduct, $sProductId, $blDisableLazyLoading);
	}

	public function truDisableCheckProduct($flag){
		self::$blTruDisableCheckProduct = (boolean) $flag;
	}
}