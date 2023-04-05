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
namespace Tru\TrustPayments\Application\Model;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use TrustPayments\Sdk\Model\CriteriaOperator;
use TrustPayments\Sdk\Model\EntityQuery;
use TrustPayments\Sdk\Model\EntityQueryFilter;
use TrustPayments\Sdk\Model\EntityQueryFilterType;
use TrustPayments\Sdk\Model\Label;
use TrustPayments\Sdk\Model\Refund;
use TrustPayments\Sdk\Model\TransactionCompletion;
use TrustPayments\Sdk\Model\TransactionLineItemVersionCreate;
use TrustPayments\Sdk\Model\TransactionState;
use TrustPayments\Sdk\Model\TransactionVoid;
use TrustPayments\Sdk\Service\RefundService;
use TrustPayments\Sdk\Service\TransactionCompletionService;
use TrustPayments\Sdk\Service\TransactionVoidService;
use Tru\TrustPayments\Core\Adapter\BasketAdapter;
use Tru\TrustPayments\Core\Adapter\SessionAdapter;
use Tru\TrustPayments\Core\Service\TransactionService;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use TrustPayments\Sdk\Model\Transaction as sdkTransaction;
use TrustPayments\Sdk\ApiException;
use Tru\TrustPayments\Core\Exception\OptimisticLockingException;

/**
 * Class Transaction.
 * Transaction model.
 */
class Transaction extends \oxbase {
	private $_sTableName = 'truTrustPayments_transaction';
	private $version = false;
	protected $dbVersion = null;
	/**
	 *
	 * @var sdkTransaction
	 */
	private $sdkTransaction;
	protected $_aSkipSaveFields = [
		'oxtimestamp',
		'truversion',
		'truupdated' 
	];

	/**
	 * Class constructor.
	 */
	public function __construct(){
		parent::__construct();
		
		$this->init($this->_sTableName);
	}

	public function getTransactionId(){
		return $this->getFieldData('trutransactionid');
	}

	public function getOrderId(){
		return $this->getFieldData('oxorderid');
	}

	public function getSdkTransaction(){
		return $this->sdkTransaction;
	}

	public function getState(){
		return $this->getFieldData('trustate');
	}

	public function getSpaceId(){
		return $this->getFieldData('truspaceid');
	}

	/**
	 *
	 * @return \oxbasket
	 */
	public function getTempBasket(){
		return unserialize(base64_decode($this->getFieldData('trutempbasket')));
	}

	public function setTempBasket($basket){
		$this->_setFieldData('trutempbasket', base64_encode(serialize($basket)));
	}

	public function getSpaceViewId(){
		return $this->getFieldData('truspaceviewid');
	}

	public function setFailureReason($value){
		$this->_setFieldData('trufailurereason', base64_encode(serialize($value)));
	}

	public function getFailureReason(){
		$value = unserialize(base64_decode($this->getFieldData('trufailurereason')));
		if (is_array($value)) {
			$value = TrustPaymentsModule::instance()->TrustPaymentsTranslate($value);
		}
		return $value;
	}

	public function getVersion(){
		return $this->version;
	}

	public function getEmailSent(){
		return $this->getFieldData('truemailsent');
	}

	public function setOrderId($value){
		$this->_setFieldData('oxorderid', $value);
	}

	protected function setState($value){
		$this->_setFieldData('trustate', $value);
	}

	protected function setSpaceId($value){
		$this->_setFieldData('truspaceid', $value);
	}

	protected function setSpaceViewId($value){
		$this->_setFieldData('truspaceviewid', $value);
	}

	protected function setTransactionId($value){
		$this->_setFieldData('trutransactionid', $value);
	}

	protected function setVersion($value){
		$this->version = $value;
	}

	protected function setSdkTransaction($value){
		$this->sdkTransaction = $value;
	}

	public function setEmailSent($value){
		$this->_setFieldData('truemailsent', $value);
	}

	public function markEmailAsSent(){
		$this->setEmailSent(true);
	}

	public function loadByOrder($orderId){
		$select = $this->buildSelectString(array(
			'oxorderid' => $orderId 
		));
		$this->_isLoaded = $this->assignRecord($select);
		$this->dbVersion = $this->getFieldData('truversion');
		return $this->_isLoaded;
	}

	/**
	 *
	 * @param \oxsession $session
	 * @return bool|object|Transaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	public static function loadPendingFromSession(\oxsession $session){
		$transaction = self::loadFromSession($session, TransactionState::PENDING);
		if (!$transaction) {
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);			$transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
			/* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
			$transaction->create();
		}
		return $transaction;
	}

	/**
	 *
	 * @param \oxsession $session
	 * @return bool|object|Transaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	public static function loadConfirmedFromSession(\oxsession $session){
		return self::loadFromSession($session, TransactionState::CONFIRMED);
	}

	/**
	 *
	 * @param \oxsession $session
	 * @return bool|object|Transaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	public static function loadFailedFromSession(\oxsession $session){
		return self::loadFromSession($session, TransactionState::FAILED);
	}

	/**
	 * Loads a transaction from the variables stored in the session, with the given state (In TrustPayments, not in DB).
	 *
	 * @param \oxsession $session
	 * @param $expectedState
	 * @return bool|object|Transaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	protected static function loadFromSession(\oxsession $session, $expectedState){
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);		$transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
		/* @var $transaction Transaction */
		$transactionId = $session->getVariable('TrustPayments_transaction_id');
		$spaceId = $session->getVariable('TrustPayments_space_id');
		$userId = $session->getVariable('TrustPayments_user_id');
		
		if ($transactionId && $spaceId && $userId == $session->getUser()->getId() && $spaceId == TrustPaymentsModule::settings()->getSpaceId()) {
			if (!$transaction->loadByTransactionAndSpace($transactionId, $spaceId)) {
				$transaction->setSpaceId($spaceId);
				$transaction->setTransactionId($transactionId);
			}
			$transaction->pull();
			if ($transaction->getState() === $expectedState) {
				$transaction->dbVersion = $transaction->getFieldData('truversion');
				return $transaction;
			}
		}
		return false;
	}

	public function loadByTransactionAndSpace($transactionId, $spaceId){
		$select = $this->buildSelectString(
				array(
					'trutransactionid' => $transactionId,
					'truspaceid' => $spaceId 
				));
		$this->_isLoaded = $this->assignRecord($select);
		$this->dbVersion = $this->getFieldData('truversion');
		return $this->_isLoaded;
	}

	public function getLabels(){
		return array(
			'transaction' => $this->getTransactionLabels(),
			'completions' => array(
				'title' => TrustPaymentsModule::instance()->translate('Completions'),
				'labelGroup' => $this->getCompletionLabels() 
			),
			'voids' => array(
				'title' => TrustPaymentsModule::instance()->translate('Voids'),
				'labelGroup' => $this->getVoidLabels() 
			),
			'refunds' => array(
				'title' => TrustPaymentsModule::instance()->translate('Refunds'),
				'labelGroup' => $this->getRefundLabels() 
			) 
		);
	}

	/**
	 * Creates a query containing a filter for the transaction id.
	 * The field name can be overwritten using the parameter, standard is transaction.id
	 *
	 * @param string $fieldName
	 * @return EntityQuery
	 */
	private function getTransactionQuery($fieldName = 'transaction.id'){
		$query = new EntityQuery();
		$filter = new EntityQueryFilter();
		/**
		 * @noinspection PhpParamsInspection
		 */
		$filter->setType(EntityQueryFilterType::LEAF);
		/**
		 * @noinspection PhpParamsInspection
		 */
		$filter->setOperator(CriteriaOperator::EQUALS);
		$filter->setFieldName($fieldName);
		/**
		 * @noinspection PhpParamsInspection
		 */
		$filter->setValue($this->getTransactionId());
		$query->setFilter($filter);
		return $query;
	}

	private function getTransactionLabels(){
		$paymentMethod = $paymentDescription = '';
		if ($this->getSdkTransaction()->getPaymentConnectorConfiguration()) {
			if ($this->getSdkTransaction()->getPaymentConnectorConfiguration()->getPaymentMethodConfiguration()) {
				$paymentDescription = TrustPaymentsModule::instance()->TrustPaymentsTranslate(
						$this->getSdkTransaction()->getPaymentConnectorConfiguration()->getPaymentMethodConfiguration()->getResolvedDescription());
				$paymentMethod = TrustPaymentsModule::instance()->TrustPaymentsTranslate(
						$this->getSdkTransaction()->getPaymentConnectorConfiguration()->getPaymentMethodConfiguration()->getResolvedTitle());
			}
			else {
				$paymentMethod = $this->getSdkTransaction()->getPaymentConnectorConfiguration()->getName();
				$paymentDescription = $this->getSdkTransaction()->getPaymentConnectorConfiguration()->getId();
			}
		}
		
		$openText = TrustPaymentsModule::instance()->translate('Open');
		$labels = array(
			'title' => TrustPaymentsModule::instance()->translate('Transaction information'),
			'labelGroup' => array(
				array(
					'title' => TrustPaymentsModule::instance()->translate("Transaction #!id", true, array(
						'!id' => $this->getTransactionId() 
					)),
					'labels' => array(
						array(
							'title' => TrustPaymentsModule::instance()->translate('Status'),
							'description' => TrustPaymentsModule::instance()->translate('Status in the Trust Payments system'),
							'value' => $this->getState() 
						),
						array(
							'title' => TrustPaymentsModule::instance()->translate('Trust Payments Link'),
							'description' => TrustPaymentsModule::instance()->translate('Open in your Trust Payments backend'),
							'value' => $this->getTrustPaymentsLink('transaction', $this->getSpaceId(), $this->getTransactionId(), $openText) 
						),
						array(
							'title' => TrustPaymentsModule::instance()->translate('Authorization amount'),
							'description' => TrustPaymentsModule::instance()->translate(
									'The amount which was authorized with the Trust Payments transaction.'),
							'value' => $this->getSdkTransaction()->getAuthorizationAmount() 
						),
						array(
							'title' => TrustPaymentsModule::instance()->translate('Payment method'),
							'description' => $paymentDescription,
							'value' => $paymentMethod 
						) 
					) 
				) 
			) 
		);
		
class_exists('oxorder');		$order = oxNew('oxorder');
		if (!$order->load($this->getOrderId())) {
			throw new \Exception("Unable to load order {$this->getOrderId()} for transaction {$this->getTransactionId()}.");
		}
		
		foreach ($order->getTrustPaymentsDownloads() as $download) {
			$labels['labelGroup'][0]['labels'][] = array(
				'title' => $download['text'],
				'description' => $download['text'],
				'value' => "<a href='{$download['link']}' target='_blank' style='text-decoration: underline;'>$openText</a>" 
			);
		}
		
		return $labels;
	}

	private function getTrustPaymentsLink($type, $space, $id, $link_text){
		$base_url = TrustPaymentsModule::settings()->getBaseUrl();
		$url = "$base_url/s/$space/payment/$type/view/$id";
		return "<a href='$url' target='_blank' style='text-decoration: underline;'>$link_text</a>";
	}

	/**
	 *
	 * @return array
	 * @throws ApiException
	 */
	private function getCompletionLabels(){
		$service = new TransactionCompletionService(TrustPaymentsModule::instance()->getApiClient());
		$completions = $service->search($this->getSpaceId(), $this->getTransactionQuery('lineItemVersion.transaction.id'));
		return $this->convertJobLabels($completions);
	}

	/**
	 *
	 * @param TransactionCompletion[]|TransactionVoid[]|Refund[] $jobs
	 * @return array
	 */
	private function convertJobLabels($jobs){
		$labelGroup = array();
		foreach ($jobs as $job) {
			$jobLabels = array(
				array(
					'title' => TrustPaymentsModule::instance()->translate('Status'),
					'description' => TrustPaymentsModule::instance()->translate('Status in the Trust Payments system.'),
					'value' => $job->getState() 
				),
				array(
					'title' => TrustPaymentsModule::instance()->translate('Trust Payments Link'),
					'description' => TrustPaymentsModule::instance()->translate('Open in your Trust Payments backend.'),
					'value' => $this->getTrustPaymentsLink($this->getJobLinkType($job), $job->getLinkedSpaceId(), $job->getId(),
							TrustPaymentsModule::instance()->translate('Open')) 
				) 
			);
			foreach ($job->getLabels() as $label) {
				$jobLabels[] = $this->convertLabel($label);
			}
			if ($job instanceof Refund) {
				$message = 'Refund #!id';
			}
			else if ($job instanceof TransactionCompletion) {
				$message = 'Completion #!id';
			}
			else if ($job instanceof TransactionVoid) {
				$message = 'Void #!id';
			}
			else {
				$message = get_class($job) . ' !id';
			}
			
			$labelGroup[$job->getId()] = array(
				'title' => TrustPaymentsModule::instance()->translate($message, true, array(
					'!id' => $job->getId() 
				)),
				'labels' => $jobLabels 
			);
		}
		return $labelGroup;
	}

	private function getJobLinkType($job){
		if ($job instanceof TransactionVoid) {
			return 'void';
		}
		else if ($job instanceof TransactionCompletion) {
			return 'completion';
		}
		else if ($job instanceof Refund) {
			return 'refund';
		}
		$type = get_class($job);
		TrustPaymentsModule::log(Logger::ERROR, "Unable to match job link type for $type.");
		return $type;
	}

	private function convertLabel(Label $label){
		/**
		 * @noinspection PhpParamsInspection
		 */
		return array(
			'title' => TrustPaymentsModule::instance()->TrustPaymentsTranslate($label->getDescriptor()->getName()),
			'description' => TrustPaymentsModule::instance()->TrustPaymentsTranslate($label->getDescriptor()->getDescription()),
			'value' => $label->getContentAsString() 
		);
	}

	/**
	 *
	 * @return array
	 * @throws ApiException
	 */
	private function getVoidLabels(){
		$service = new TransactionVoidService(TrustPaymentsModule::instance()->getApiClient());
		$voids = $service->search($this->getSpaceId(), $this->getTransactionQuery());
		return $this->convertJobLabels($voids);
	}

	/**
	 *
	 * @return array
	 * @throws ApiException
	 */
	private function getRefundLabels(){
		$service = new RefundService(TrustPaymentsModule::instance()->getApiClient());
		$refunds = $service->search($this->getSpaceId(), $this->getTransactionQuery());
		return $this->convertJobLabels($refunds);
	}

	/**
	 *
	 * @throws ApiException
	 * @throws \Exception
	 */
	public function pull(){
		TrustPaymentsModule::log(Logger::DEBUG, "Start transaction pull.");
		if (!$this->getTransactionId()) {
			throw new \Exception('Transaction id must be set to pull.');
		}
		$this->apply(TransactionService::instance()->read($this->getTransactionId(), $this->getSpaceId()));
		TrustPaymentsModule::log(Logger::DEBUG, "Transaction pull complete.");
	}

	/**
	 *
	 * @param bool $confirm
	 * @return sdkTransaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	public function updateFromSession($confirm = false){
		TrustPaymentsModule::log(Logger::DEBUG, "Start update from session.");
		$this->pull(); // ensure updateable
		if ($this->getState() !== TransactionState::PENDING) {
			throw new \Exception('Transaction not in state PENDING may no longer be updated:' . $this->getTransactionId());
		}
		
		$adapter = new SessionAdapter($this->getSession());
		$transaction = TransactionService::instance()->update($adapter->getUpdateData($this), $confirm);
		$this->apply($transaction);
		TrustPaymentsModule::log(Logger::DEBUG, "Complete update from session.");
		return $transaction;
	}
	
	public function getPaymentPageUrl() {
		return TransactionService::instance()->getPaymentPageUrl($this->getTransactionId(), $this->getSpaceId());
	}

	public function updateLineItems(){
		TrustPaymentsModule::log(Logger::DEBUG, "Start update line items.");
class_exists('oxorder');		$order = oxNew('oxorder');
		/* @var $order\oxorder */
		if (!$order->load($this->getOrderId())) {
			throw new \Exception("Unable to load order {$this->getOrderId()} for transaction {$this->getTransactionId()}.");
		}
		$adapter = new BasketAdapter($order->getTrustPaymentsBasket());
		$adapter->getLineItemData();
		$update = new TransactionLineItemVersionCreate();
		$update->setLineItems($adapter->getLineItemData());
		$update->setTransaction($this->getTransactionId());
	    	$update->setExternalId(uniqid($this->getTransactionId()));
		TransactionService::instance()->updateLineItems($this->getSpaceId(), $update);
		$this->pull();
		TrustPaymentsModule::log(Logger::DEBUG, "Complete update line items.");
		return $this->getSdkTransaction();
	}

	/**
	 *
	 * @return sdkTransaction
	 * @throws ApiException
	 * @throws \Exception
	 */
	public function create(){
		TrustPaymentsModule::log(Logger::DEBUG, "Start transaction create.");
		$adapter = new SessionAdapter($this->getSession());
		$transaction = TransactionService::instance()->create($adapter->getCreateData());
		$this->dbVersion = 0;
		$this->apply($transaction);
		
		TrustPaymentsModule::log(Logger::DEBUG, "Complete transaction create.");
		return $transaction;
	}

	/**
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function save(){
		TrustPaymentsModule::log(Logger::DEBUG, "Start transaction save.");
		// only save to db with order, otherwise save relevant ids to session.
		if ($this->getOrderId()) {
			TrustPaymentsModule::log(Logger::DEBUG, "Saving to database.");
			return parent::save();
		}
		else if ($this->getSession()->getUser()) {
			TrustPaymentsModule::log(Logger::DEBUG, "Saving to session.");
			$this->getSession()->setVariable('TrustPayments_transaction_id', $this->getTransactionId());
			$this->getSession()->setVariable('TrustPayments_space_id', $this->getSpaceId());
			$this->getSession()->setVariable('TrustPayments_user_id', $this->getSession()->getUser()->getId());
		}
		return false;
	}

	/**
	 *
	 * @param sdkTransaction $transaction
	 * @throws \Exception
	 */
	protected function apply(sdkTransaction $transaction){
		$this->setSdkTransaction($transaction);
		$this->setTransactionId($transaction->getId());
		$this->setVersion($transaction->getVersion());
		$this->setState($transaction->getState());
		$this->setSpaceId($transaction->getLinkedSpaceId());
		$this->setSpaceViewId($transaction->getSpaceViewId());
		$this->save();
	}

	/**
	 * Overwrite _update method to introduce optimistic locking.
	 *
	 * {@inheritdoc}
	 * @see \OxidEsales\EshopCommunity\Core\Model\BaseModel::_update()
	 */
	protected function _update(){
		//do not allow derived item update
		if (!$this->allowDerivedUpdate()) {
			return false;
		}
		
		if (!$this->getId()) {
class_exists('oxobjectexception');			$exception = oxNew('oxobjectexception');
			$exception->setMessage('EXCEPTION_OBJECT_OXIDNOTSET');
			$exception->setObject($this);
			throw $exception;
		}
		$coreTableName = $this->getCoreTableName();
		
		$idKey = \oxregistry::getUtils()->getArrFldName($coreTableName . '.oxid');
		$this->$idKey = new \oxfield($this->getId(), \oxfield::T_RAW);
		$database = \oxdb::getDb();
		
		$dbVersion = $this->dbVersion;
		if (!$dbVersion) {
			$dbVersion = 0;
		}
		$updateQuery = "update {$coreTableName} set " . $this->_getUpdateFields() . " , truversion=truversion + 1 " .
				 " where {$coreTableName}.oxid = " . $database->quote($this->getId()) .
				 " and {$coreTableName}.truversion = {$dbVersion}";
		TrustPaymentsModule::log(Logger::DEBUG, "Updating  transaction with query [$updateQuery]");

		$this->beforeUpdate();
		$affected = \oxdb::getDb()->execute($updateQuery);

		if ($affected === 0) {
			throw new OptimisticLockingException($this->getId(), $this->_sTableName, $updateQuery);
		}
		
		return true;
	}
}