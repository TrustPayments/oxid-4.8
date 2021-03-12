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
namespace Tru\TrustPayments\Core\Service;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use TrustPayments\Sdk\Model\EntityQuery;
use TrustPayments\Sdk\Model\TransactionCreate;
use TrustPayments\Sdk\Model\TransactionLineItemUpdateRequest;
use TrustPayments\Sdk\Model\TransactionPending;
use TrustPayments\Sdk\Service\TransactionInvoiceService;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use \TrustPayments\Sdk\Service\TransactionService as SdkTransactionService;
use \TrustPayments\Sdk\Service\TransactionIframeService;
use \TrustPayments\Sdk\Service\TransactionPaymentPageService;

/**
 * Class TransactionService
 * Handles api interactions regarding transaction.
 *
 * @codeCoverageIgnore
 */
class TransactionService extends AbstractService {
	private $service;
	private $invoiceService;
	private $paymentPageService;
	private $iframeService;

	protected function getService(){
		if (!$this->service) {
			$this->service = new SdkTransactionService(TrustPaymentsModule::instance()->getApiClient());
		}
		return $this->service;
	}

	/**
	 *
	 * @return TransactionInvoiceService
	 */
	protected function getInvoiceService(){
		if (!$this->invoiceService) {
			$this->invoiceService = new TransactionInvoiceService(TrustPaymentsModule::instance()->getApiClient());
		}
		return $this->invoiceService;
	}
	
	/**
	 *
	 * @return TransactionPaymentPageService
	 */
	protected function getPaymentPageService(){
		if (!$this->paymentPageService) {
			$this->paymentPageService = new TransactionPaymentPageService(TrustPaymentsModule::instance()->getApiClient());
		}
		return $this->paymentPageService;
	}
	
	/**
	 *
	 * @return TransactionIframeService
	 */
	protected function getIframeService(){
		if (!$this->iframeService) {
			$this->iframeService = new TransactionIframeService(TrustPaymentsModule::instance()->getApiClient());
		}
		return $this->iframeService;
	}

	/**
	 * Reads a transaction entity from TrustPayments
	 *
	 * @param $transactionId
	 * @param $spaceId
	 * @return \TrustPayments\Sdk\Model\Transaction
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function read($transactionId, $spaceId){
		return $this->getService()->read($spaceId, $transactionId);
	}

	/**
	 *
	 * @param TransactionCreate $transaction
	 * @return \TrustPayments\Sdk\Model\Transaction
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function create(TransactionCreate $transaction){
		return $this->getService()->create(TrustPaymentsModule::settings()->getSpaceId(), $transaction);
	}

	/**
	 *
	 * @param $transactionId
	 * @param $spaceId
	 * @return \TrustPayments\Sdk\Model\TransactionInvoice
	 * @throws \Exception
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function getInvoice($transactionId, $spaceId){
		$query = new EntityQuery();
		$query->setFilter($this->createEntityFilter('completion.lineItemVersion.transaction.id', $transactionId));
		$query->setNumberOfEntities(1);
		$invoices = $this->getInvoiceService()->search($spaceId, $query);
		if (empty($invoices)) {
			throw new \Exception("No transaction invoice found for transaction $transactionId / $spaceId.");
		}
		return $invoices[0];
	}

	/**
	 *
	 * @param string $transactionId
	 * @param string $spaceId
	 * @return string
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function getPaymentPageUrl($transactionId, $spaceId){
		return $this->getPaymentPageService()->paymentPageUrl($spaceId, $transactionId);
	}

	/**
	 *
	 * @param TransactionPending $transaction
	 * @param bool $confirm
	 * @return \TrustPayments\Sdk\Model\Transaction
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function update(TransactionPending $transaction, $confirm = false){
		if ($confirm) {
			return $this->getService()->confirm(TrustPaymentsModule::settings()->getSpaceId(), $transaction);
		}
		else {
			return $this->getService()->update(TrustPaymentsModule::settings()->getSpaceId(), $transaction);
		}
	}

	public function updateLineItems($spaceId, TransactionLineItemUpdateRequest $updateRequest){
		return $this->getService()->updateTransactionLineItems($spaceId, $updateRequest);
	}

	/**
	 *
	 * @param $transactionId
	 * @param $spaceId
	 * @return string
	 * @throws \TrustPayments\Sdk\ApiException
	 */
	public function getJavascriptUrl($transactionId, $spaceId){
		return $this->getIframeService()->javascriptUrl($spaceId, $transactionId);
	}
}