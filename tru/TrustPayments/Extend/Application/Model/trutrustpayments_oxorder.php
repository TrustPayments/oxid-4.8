<?php
/**
 * TrustPayments OXID
 *
 * This OXID module enables to process payments with TrustPayments (https://www.trustpayments.com//).
 *
 * @package Whitelabelshortcut\TrustPayments
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
require_once (OX_BASE_PATH . "modules/tru/TrustPayments/autoload.php");

use \TrustPayments\Sdk\Model\TransactionState;
use \Tru\TrustPayments\Application\Model\Transaction;
use \Tru\TrustPayments\Core\TrustPaymentsModule;
use Monolog\Logger;

/**
 * Class Order.
 * Extends \oxorder.
 *
 * @mixin \oxorder
 */
class trutrustpayments_oxorder extends trutrustpayments_oxorder_parent {
	private $confirming = false;
	private static $wleStateOrder = [
		TransactionState::CREATE => 0,
		TransactionState::PENDING => 1,
		TransactionState::CONFIRMED => 2,
		TransactionState::PROCESSING => 3,
		TransactionState::AUTHORIZED => 4,
		TransactionState::COMPLETED => 5,
		TransactionState::FULFILL => 6,
		TransactionState::DECLINE => 6,
		TransactionState::VOIDED => 6,
		TransactionState::FAILED => 6 
	];

	public function getTrustPaymentsBasket(){
		// copied from recalculateOrder, minus call of finalizeOrder, and adding new articles.
		$oBasket = $this->_getOrderBasket();
		/* @noinspection PhpParamsInspection */
		$this->_addOrderArticlesToBasket($oBasket, $this->getOrderArticles(true));
		$oBasket->calculateBasket(true);
		return $oBasket;
	}

	/**
	 * Sets the oxtransstatus and oxfolder according to the given TransactionState
	 *
	 * @param string $state TransactionState enum
	 */
	public function setTrustPaymentsState($state){
		if (!$this->isTruOrder()) {
			TrustPaymentsModule::log(Logger::WARNING,
					"Attempted to call " . __METHOD__ . " on non-TrustPayments order {$this->getId()}, skipping.");
			return;
		}
		$oldState = substr($this->getFieldData('OXTRANSSTATUS'), strlen('TRUSTPAYMENTS_'));
		if (self::$wleStateOrder[$oldState] > self::$wleStateOrder[$state]) {
			throw new \Exception("Cannot move order from state $oldState to $state.");
		}
		$this->_setFieldData('OXTRANSSTATUS', 'TRUSTPAYMENTS_' . $state);
		$this->_setFieldData('OXFOLDER', TrustPaymentsModule::getMappedFolder($state));
	}

	/**
	 * Sends the confirmation email.
	 *
	 * @throws \Exception
	 */
	public function TrustPaymentsAuthorize(){
		if (!$this->isTruOrder()) {
			TrustPaymentsModule::log(Logger::WARNING,
					"Attempted to call " . __METHOD__ . " on non-TrustPayments order {$this->getId()}, skipping.");
			return;
		}
		$basket = $this->getTrustPaymentsTransaction()->getTempBasket();
		$basket->onUpdate();
		$basket->calculateBasket();
		$res = $this->_sendOrderByEmail($this->getOrderUser(), $basket, $this->getPaymentType());
		if ($res === self::ORDER_STATE_OK) {
			$this->getTrustPaymentsTransaction()->setTempBasket(null);
			$this->getTrustPaymentsTransaction()->save();
		}
	}

	public function setTrustPaymentsPaid(){
		if (!$this->isTruOrder()) {
			TrustPaymentsModule::log(Logger::WARNING,
					"Attempted to call " . __METHOD__ . " on non-TrustPayments order {$this->getId()}, skipping.");
			return;
		}
		$this->_setFieldData('oxpaid', date('Y-m-d H:i:s'), \oxfield::T_RAW);
	}

	/**
	 * Sets the order state to the given state, and saves the message on the associated transaction.
	 *
	 * @param $message
	 * @param $state
	 * @param bool $cancel If the order should be cancelled
	 * @param bool $rethrow if exceptions should be thrown.
	 */
	public function TrustPaymentsFail($message, $state, $cancel = false, $rethrow = false){
		if (!$this->isTruOrder()) {
			TrustPaymentsModule::log(Logger::WARNING,
					"Attempted to call " . __METHOD__ . " on non-TrustPayments order {$this->getId()}, skipping.");
			return;
		}
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);		$transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
		/* @var $transaction Transaction */
		if ($transaction->loadByOrder($this->getId())) {
			try {
				$transaction->setFailureReason($message);
				$transaction->save();
			}
			catch (\Exception $e) {
				// treat optimisticlockingexception equally.
				TrustPaymentsModule::log(Logger::ERROR, "Unable to save transaction with ID {$transaction->getId()}: {$e->getMessage()}.");
				if ($rethrow) {
					throw $e;
				}
			}
		}
		else {
			TrustPaymentsModule::log(Logger::ERROR, "Unable to save failure message '{$message}' on transaction for order {$this->getId()}.");
		}
		$this->getSession()->deleteVariable("sess_challenge"); // allow new orders
		try {
			$this->setTrustPaymentsState($state);
			if ($cancel) {
				$this->cancelOrder();
			}
		}
		catch (\Exception $e) {
			TrustPaymentsModule::log(Logger::ERROR, "Unable to cancel order: {$e->getMessage()}.");
			if ($rethrow) {
				throw $e;
			}
		}
	}

	public function getTrustPaymentsDownloads(){
		$downloads = array();
		if ($this->isTruOrder()) {
			$transaction = $this->getTrustPaymentsTransaction();
			if ($transaction && in_array($transaction->getState(),
					array(
						TransactionState::COMPLETED,
						TransactionState::FULFILL,
						TransactionState::DECLINE 
					))) {
				if (TrustPaymentsModule::settings()->isDownloadInvoiceEnabled()) {
					$downloads[] = array(
						'link' => TrustPaymentsModule::getControllerUrl('tru_trustPayments_Pdf', 'invoice',
								$this->getId()),
						'text' => TrustPaymentsModule::instance()->translate('Download Invoice') 
					);
				}
				if (TrustPaymentsModule::settings()->isDownloadPackingEnabled()) {
					$downloads[] = array(
						'link' => TrustPaymentsModule::getControllerUrl('tru_trustPayments_Pdf', 'packingSlip',
								$this->getId()),
						'text' => TrustPaymentsModule::instance()->translate('Download Packing Slip') 
					);
				}
			}
		}
		return $downloads;
	}

	public function setConfirming($confirming = true){
		$this->confirming = $confirming;
	}

	public function finalizeOrder(\oxbasket $oBasket, $oUser, $blRecalculatingOrder = false){
		// business as usual
		if (!$this->isTruOrder($oBasket)) {
			return $this->_Order_finalizeOrder_parent($oBasket, $oUser, $blRecalculatingOrder);
		}
		
		// suppress duplicate finalize
		if ($this->getFieldData('oxtransstatus') === 'TRUSTPAYMENTS_' . TransactionState::PENDING) {
			if ($this->confirming) {
				return self::ORDER_STATE_OK;
			}
			else {
				$transaction = $this->getTrustPaymentsTransaction();
				if($transaction) {
					TrustPaymentsModule::instance()->getUtils()->redirect($transaction->getPaymentPageUrl());
					exit();
				}
				return self::ORDER_STATE_PAYMENTERROR;
			}
		}
		
		// finalize order so necessary data is present
		$result = $this->_Order_finalizeOrder_parent($oBasket, $oUser, $blRecalculatingOrder);
		if ($result == self::ORDER_STATE_OK && !$blRecalculatingOrder) {
			$result = 'TRUSTPAYMENTS_' . TransactionState::PENDING;
			$this->_setOrderStatus($result);
			
			// update transaction, confirm transaction, and redirect away
			if (!$this->confirming) {
				TrustPaymentsModule::log(Logger::ERROR, "Attempted to finalize order without confirmation. Redirecting to payment page.",
						$this);
				$transaction = Transaction::loadPendingFromSession($this->getSession());
				$transaction->setTempBasket($this->getBasket());
				$transaction->setOrderId($this->getId());
				$transaction->updateFromSession(true);
				TrustPaymentsModule::instance()->getUtils()->redirect($transaction->getPaymentPageUrl());
				exit();
			}
		}
		
		return $result;
	}

	protected function _Order_finalizeOrder_parent(\oxbasket $oBasket, $oUser, $blRecalculatingOrder = false){
		return parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
	}

	protected function _sendOrderByEmail($oUser = null, $oBasket = null, $oPayment = null){
		if ($this->isTruOrder() && (!TrustPaymentsModule::isAuthorizedState($this->getFieldData('oxtransstatus')) ||
				 !TrustPaymentsModule::settings()->isEmailConfirmationActive())) {
			return self::ORDER_STATE_OK;
		}
		
		return $this->_Order_sendOrderByEmail_parent($oUser, $oBasket, $oPayment);
	}

	protected function _sendOrderByEmailForced($oUser = null, $oBasket = null, $oPayment = null){
class_exists(\oxbasketItem::class);		$basketItem = oxNew(\oxbasketItem::class);
		/* @var $basketItem \Tru\TrustPayments\Extend\Application\Model\BasketItem */
		$basketItem->truDisableCheckProduct(true);
		
		$result = $this->_sendOrderByEmail($oUser, $oBasket, $oPayment);
		
		$basketItem->truDisableCheckProduct(false);
		
		return $result;
	}

	protected function _Order_sendOrderByEmail_parent($oUser = null, $oBasket = null, $oPayment = null){
		return parent::_sendOrderByEmail($oUser, $oBasket, $oPayment);
	}

	public function isTruOrder($basket = null){
		$paymentType = $this->getFieldData('oxpaymenttype');
		if (empty($paymentType)) {
			if ($this->getBasket()) {
				$paymentType = $this->getBasket()->getPaymentId();
			}
			else if ($basket instanceof \oxbasket) { // TODO as is in 4.10 base, directly use oxBasket? Or use controller pattern?
				$paymentType = $basket->getPaymentId();
			}
		}
		return substr($paymentType, 0, strlen(TrustPaymentsModule::PAYMENT_PREFIX)) === TrustPaymentsModule::PAYMENT_PREFIX;
	}

	public function getTrustPaymentsTransaction(){
		if ($this->getId()) {
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);			$transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
			/* @var $transaction Transaction */
			if ($transaction->loadByOrder($this->getId())) {
				return $transaction;
			}
		}
		return null;
	}
}