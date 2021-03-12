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
namespace Tru\TrustPayments\Core\Adapter;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use TrustPayments\Sdk\Model\AbstractTransactionPending;
use TrustPayments\Sdk\Model\TransactionCreate;
use TrustPayments\Sdk\Model\TransactionPending;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Application\Model\Transaction;
use TrustPayments\Sdk\Model\LineItemCreate;
use TrustPayments\Sdk\Model\LineItemType;

/**
 * Class SessionAdapter
 * Converts Oxid Session Data into data which can be fed into the TrustPayments SDK.
 *
 * @codeCoverageIgnore
 */
class SessionAdapter implements ITransactionServiceAdapter {
	private $session = null;
	private $basketAdapter = null;
	private $addressAdapter = null;

	/**
	 * SessionAdapter constructor.
	 *
	 * Checks if user is logged in and basket is present as well, and throws an exception if either is not present.
	 *
	 * @param \oxsession $session
	 * @throws \Exception
	 */
	public function __construct(\oxsession $session){
		if (!$session->getUser() || !$session->getBasket()) {
			throw new \Exception("User must be logged in and basket must be present.");
		}
		$this->session = $session;
		$this->basketAdapter = new BasketAdapter($session->getBasket());
		$this->addressAdapter = new AddressAdapter($session->getUser()->getSelectedAddress(), $session->getUser());
	}

	public function getCreateData(){
		$transactionCreate = new TransactionCreate();
		if (isset($_COOKIE['TrustPayments_device_id'])) {
			$transactionCreate->setDeviceSessionIdentifier($_COOKIE['TrustPayments_device_id']);
		}
		$transactionCreate->setAutoConfirmationEnabled(false);
		$transactionCreate->setChargeRetryEnabled(false);
		$this->applyAbstractTransactionData($transactionCreate);
		return $transactionCreate;
	}

	public function getUpdateData(Transaction $transaction){
		$transactionPending = new TransactionPending();
		$transactionPending->setId($transaction->getTransactionId());
		$transactionPending->setVersion($transaction->getVersion());
		$this->applyAbstractTransactionData($transactionPending);
		
		if ($transaction->getOrderId()) {
			$transactionPending->setFailedUrl(
					TrustPaymentsModule::getControllerUrl('order', 'truError', $transaction->getOrderId(), true));
class_exists('oxorder');			$order = oxNew('oxorder');
			/* @var $order \oxorder */
			if ($order->load($transaction->getOrderId())) {
				$transactionPending->setMerchantReference($order->oxorder__oxordernr->value);
				$transactionPending->setAllowedPaymentMethodConfigurations(
						array(
							TrustPaymentsModule::extractTrustPaymentsId($order->oxorder__oxpaymenttype->value) 
						));
				$totalDifference = $this->getTotalsDifference($transactionPending->getLineItems(), $order);
				if($totalDifference) {
					if(TrustPaymentsModule::settings()->enforceLineItemConsistency()) {
						throw new \Exception(TrustPaymentsModule::instance()->translate('Totals mismatch, please contact merchant or use another payment method.'));
					}
					else {
						$lineItems = $transactionPending->getLineItems();
						$lineItems[] = $this->createRoundingAdjustment($totalDifference);
						$transactionPending->setLineItems($lineItems);
					}
				}
			}
		}
		
		return $transactionPending;
	}

	private function applyAbstractTransactionData(AbstractTransactionPending $transaction){
		$transaction->setCustomerId($this->session->getUser()->getId());
		$transaction->setCustomerEmailAddress($this->session->getUser()->getFieldData('oxusername'));
		/**
		 * @noinspection PhpUndefinedFieldInspection
		 */
		$transaction->setCurrency($this->session->getBasket()->getBasketCurrency()->name);
		$transaction->setLineItems($this->basketAdapter->getLineItemData());
		$transaction->setBillingAddress($this->addressAdapter->getBillingAddressData());
		$transaction->setShippingAddress($this->addressAdapter->getShippingAddressData());
		$transaction->setLanguage(\oxregistry::getLang()->getLanguageAbbr());
		$transaction->setSuccessUrl(TrustPaymentsModule::getControllerUrl('thankyou', null, null, true));
		$transaction->setFailedUrl(TrustPaymentsModule::getControllerUrl('order', 'truError', null, true));
	}
	
	private function getTotalsDifference(array $lineItems, \oxorder $order) {
		$total = 0;
		foreach($lineItems as $lineItem) {
			$total += $lineItem->getAmountIncludingtax();
		}
		return \oxregistry::getUtils()->fRound($total - $order->getTotalOrderSum(), $order->getOrderCurrency());
	}
	
	private function createRoundingAdjustment($amount)
	{
		$lineItem = new LineItemCreate();
		/** @noinspection PhpParamsInspection */
		$lineItem->setType(LineItemType::FEE);
		$lineItem->setAmountIncludingTax($amount);
		$lineItem->setName(TrustPaymentsModule::instance()->translate('Rounding Adjustment'));
		$lineItem->setQuantity(1);
		$lineItem->setUniqueId('rounding_adjustment');
		$lineItem->setSku('rounding_adjustment');
		return $lineItem;
	}
	
}