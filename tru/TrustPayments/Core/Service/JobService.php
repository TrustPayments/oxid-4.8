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
use TrustPayments\Sdk\ApiException;
use TrustPayments\Sdk\Model\TransactionCompletionState;
use TrustPayments\Sdk\Service\TransactionCompletionService;
use TrustPayments\Sdk\Service\TransactionVoidService;
use Tru\TrustPayments\Application\Model\AbstractJob;
use Tru\TrustPayments\Application\Model\Transaction;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class PaymentService
 * Handles api interactions regarding payment methods.
 *
 * @codeCoverageIgnore
 */
abstract class JobService extends AbstractService
{
    /**
     * @return \TrustPayments\Sdk\Service\RefundService|TransactionVoidService|TransactionCompletionService
     */
    protected abstract function getService();

    protected abstract function processSend(AbstractJob $job);

    public function send(AbstractJob $job)
    {
        try {
            $response = $this->processSend($job);
            $job->apply($response);
            return true;
        } catch (\Exception $e) {
            $job->setState(static::getFailedState());
            $job->setFailureReason($e->getMessage());
        }
        try {
            $job->save();
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Unable to save job post send: {$e->getMessage()} - {$e->getTraceAsString()}. " . print_r($job, true));
        }
        return false;
    }

    /**
     * Creates a job for the given type.
     * Checks the given transaction for correct state,
     * If a created, unsent job already exists, that is returned instead.
     *
     * @param Transaction $transaction
     * @param bool $save (If the job should be saved. e.g. with RefundJob further data will be applied
     * @throws ApiException
     * @throws \Exception
     * @return AbstractJob
     */
    public function create(Transaction $transaction, $save = true)
    {
        if (!in_array($transaction->getState(), $this->getSupportedTransactionStates())) {
            $states = implode(", ", $this->getSupportedTransactionStates());
            throw new \Exception("Job may only be created if transaction in one of states: $states.");
        }
class_exists($this->getJobType());        $job = oxNew($this->getJobType());
        /* @var $job AbstractJob */
        if($job->loadByOrder($transaction->getOrderId(), static::getPendingStates())) {
            throw new \Exception("Job may only be created if transaction no pending jobs exist.");
        }
        if (!$job->loadByOrder($transaction->getOrderId(), array(static::getCreationState()))) {
            $job->setState(static::getCreationState());
            $job->setOrderId($transaction->getOrderId());
            $job->setTransactionId($transaction->getTransactionId());
            $job->setSpaceId($transaction->getSpaceId());
            if ($save) {
                $job->save();
            }
        }
        return $job;
    }

    public function read(AbstractJob $job)
    {
        return $this->getService()->read($job->getSpaceId(), $job->getJobId());
    }

    /**
     * Gets the state that newly created jobs should have
     *
     * @return string
     */
    public static function getCreationState()
    {
        return TransactionCompletionState::CREATE;
    }

    public static function getPendingStates() {
        return array(
            TransactionCompletionState::PENDING
        );
    }

    public static function getFailedState()
    {
        return TransactionCompletionState::FAILED;
    }

    protected abstract function getJobType();

    public abstract function resendAll();

    public abstract function getSupportedTransactionStates();
}