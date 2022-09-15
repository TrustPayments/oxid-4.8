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

namespace Tru\TrustPayments\Application\Controller\Admin;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use TrustPayments\Sdk\Model\RefundState;
use TrustPayments\Sdk\Model\TransactionCompletionState;
use TrustPayments\Sdk\Model\TransactionVoidState;
use Tru\TrustPayments\Core\Exception\OptimisticLockingException;
use Tru\TrustPayments\Core\Service\CompletionService;
use Tru\TrustPayments\Core\Service\RefundService;
use Tru\TrustPayments\Core\Service\VoidService;
use Tru\TrustPayments\Core\TrustPaymentsModule;


/**
 * Class Transaction.
 */
class Transaction extends \oxadmindetails
{

    /**
     * Controller template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'truTrustPaymentsTransaction.tpl';

    /**
     * @return string
     */
    public function render()
    {
        parent::render();
        $this->_aViewData['tru_trustPayments_enabled'] = false;
        $orderId = $this->getEditObjectId();
        try {
            if ($orderId != '-1' && isset($orderId)) {
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);                $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
                /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
                if ($transaction->loadByOrder($orderId)) {
                    $transaction->pull();
                    $this->_aViewData['labelGroupings'] = $transaction->getLabels();
                    $this->_aViewData['tru_trustPayments_enabled'] = true;
                    return $this->_sThisTemplate;
                } else {
                    throw new \Exception(TrustPaymentsModule::instance()->translate('Not a Trust Payments order.'));
                }
            } else {
                throw new \Exception(TrustPaymentsModule::instance()->translate('No order selected'));
            }
        } catch (OptimisticLockingException $e) {
            $this->_aViewData['tru_trustPayments_enabled'] = $e->getMessage();
            return $this->_sThisTemplate;
        } catch (\Exception $e) {
            $this->_aViewData['tru_error'] = $e->getMessage();
            return 'truTrustPaymentsError.tpl';
        }
    }

    /**
     * Creates and sends a completion job.
     */
    public function complete()
    {
    	TrustPaymentsModule::log(Logger::DEBUG, "Start complete.");
        $oxid = $this->getEditObjectId();
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        if ($transaction->loadByOrder($oxid)) {
        	TrustPaymentsModule::log(Logger::DEBUG, "Loaded by order.");
            try {
            	$transaction->updateLineItems();
            	TrustPaymentsModule::log(Logger::DEBUG, "Updated items.");
            	$job = CompletionService::instance()->create($transaction);
            	TrustPaymentsModule::log(Logger::DEBUG, "Created job.");
            	CompletionService::instance()->send($job);
            	TrustPaymentsModule::log(Logger::DEBUG, "Sent job.");
                if ($job->getState() === TransactionCompletionState::FAILED) {
                	TrustPaymentsModule::getUtilsView()->addErrorToDisplay($job->getFailureReason());
                } else {
                    $this->_aViewData['message'] = TrustPaymentsModule::instance()->translate("Successfully created and sent completion job !id.", true, array('!id' => $job->getJobId()));
                }
            } catch (\Exception $e) {
                TrustPaymentsModule::log(Logger::ERROR, "Exception occurred while completing transaction: {$e->getMessage()} - {$e->getTraceAsString()}");
                TrustPaymentsModule::getUtilsView()->addErrorToDisplay($e->getMessage()); // To set error
            }
        } else {
            $error = "Unable to load transaction by order $oxid for completion.";
            TrustPaymentsModule::log(Logger::ERROR, $error);
            TrustPaymentsModule::getUtilsView()->addErrorToDisplay($error); // To set error
        }
    }

    /**
     * Creates and sends a void job.
     *
     */
    public function void()
    {
    	TrustPaymentsModule::log(Logger::DEBUG, "Start void.");
        $oxid = $this->getEditObjectId();
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        if ($transaction->loadByOrder($oxid)) {
        	TrustPaymentsModule::log(Logger::DEBUG, "Loaded by order.");
        	try {
        		$transaction->pull();
        		$job = VoidService::instance()->create($transaction);
        		TrustPaymentsModule::log(Logger::DEBUG, "Created job.");
        		VoidService::instance()->send($job);
        		TrustPaymentsModule::log(Logger::DEBUG, "Sent job.");
                if ($job->getState() === TransactionVoidState::FAILED) {
                	TrustPaymentsModule::getUtilsView()->addErrorToDisplay($job->getFailureReason());
                } else {
                    $this->_aViewData['message'] = TrustPaymentsModule::instance()->translate("Successfully created and sent void job !id.", true, array('!id' => $job->getJobId()));
                }
            } catch (\Exception $e) {
                TrustPaymentsModule::log(Logger::ERROR, "Exception occurred while completing transaction: {$e->getMessage()} - {$e->getTraceAsString()}");
                TrustPaymentsModule::getUtilsView()->addErrorToDisplay($e->getMessage()); // To set error
            }
        } else {
            $error = "Unable to load transaction by order $oxid for completion.";
            TrustPaymentsModule::log(Logger::ERROR, $error);
            TrustPaymentsModule::getUtilsView()->addErrorToDisplay($error); // To set error
        }
    }

    /**
     * Checks if the transaction associated with the given order id is in the correct state for completion, and checks if any completion jobs are currently running.
     *
     * @param $orderId
     * @return bool
     */
    public function canComplete($orderId)
    {
        try {
class_exists(\Tru\TrustPayments\Application\Model\CompletionJob::class);        	$job = oxNew(\Tru\TrustPayments\Application\Model\CompletionJob::class);
            /* @var $job \Tru\TrustPayments\Application\Model\CompletionJob */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);            $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
            /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
            $transaction->loadByOrder($orderId);
            $transaction->pull();
            return !$job->loadByOrder($orderId, array(TransactionCompletionState::PENDING)) &&
                in_array($transaction->getState(), CompletionService::instance()->getSupportedTransactionStates());
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Unable to check completion possibility: {$e->getMessage()} - {$e->getTraceAsString()}");
        }
        return false;
    }

    /**
     * Checks if the transaction associated with the given order id is in the correct state for refund, and checks if any refund jobs are currently running.
     *
     * @param $orderId
     * @return bool
     */
    public function canRefund($orderId)
    {
        try {
class_exists(\Tru\TrustPayments\Application\Model\RefundJob::class);            $job = oxNew(\Tru\TrustPayments\Application\Model\RefundJob::class);
            /* @var $job \Tru\TrustPayments\Application\Model\RefundJob */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);            $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
            /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
            $transaction->loadByOrder($orderId);
            $transaction->pull();
            return !$job->loadByOrder($orderId, array(RefundState::MANUAL_CHECK, RefundState::PENDING)) &&
                in_array($transaction->getState(), RefundService::instance()->getSupportedTransactionStates()) && !empty(RefundService::instance()->getReducedItems($transaction));
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Unable to check completion possibility: {$e->getMessage()} - {$e->getTraceAsString()}");
        }
        return false;
    }

    /**
     * Checks if the transaction associated with the given order id is in the correct state for void, and checks if any void jobs are currently running.
     * @param $orderId
     * @return bool
     */
    public function canVoid($orderId)
    {
        try {
class_exists(\Tru\TrustPayments\Application\Model\VoidJob::class);        	$job = oxNew(\Tru\TrustPayments\Application\Model\VoidJob::class);
            /* @var $job \Tru\TrustPayments\Application\Model\VoidJob */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);            $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
            /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
            $transaction->loadByOrder($orderId);
            $transaction->pull();
            return !$job->loadByOrder($orderId, array(TransactionVoidState::PENDING)) &&
                in_array($transaction->getState(), VoidService::instance()->getSupportedTransactionStates());
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Unable to check void possibility: {$e->getMessage()} - {$e->getTraceAsString()}");
        }
        return false;
    }
}