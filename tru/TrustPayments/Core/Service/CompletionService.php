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
use TrustPayments\Sdk\Model\TransactionState;
use TrustPayments\Sdk\Service\TransactionCompletionService;
use Tru\TrustPayments\Application\Model\AbstractJob;
use Tru\TrustPayments\Application\Model\CompletionJob;
use Tru\TrustPayments\Application\Model\Transaction;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class CompletionService
 */
class CompletionService extends JobService
{

    private $service;

    protected function getService()
    {
        if ($this->service === null) {
            $this->service = new TransactionCompletionService(TrustPaymentsModule::instance()->getApiClient());
        }
        return $this->service;
    }


    protected function getJobType()
    {
        return CompletionJob::class;
    }

    public function getSupportedTransactionStates()
    {
        return array(
            TransactionState::AUTHORIZED
        );
    }

    protected function processSend(AbstractJob $job)
    {
        if (!$job instanceof CompletionJob) {
            throw new \Exception("Invalid job type supplied.");
        }
        return $this->getService()->completeOnline($job->getSpaceId(), $job->getTransactionId());
    }

    public function resendAll()
    {
        $errors = array();
class_exists(CompletionJob::class);        $completion = oxNew(CompletionJob::class);
        /* @var $completion \Tru\TrustPayments\Application\Model\CompletionJob */
        $notSent = $completion->loadNotSentIds();
        foreach ($notSent as $job) {
            if ($completion->loadByJob($job['TRUJOBID'], $job['TRUSPACEID'])) {
class_exists(Transaction::class);                $transaction = oxNew(Transaction::class);
                /* @var $transaction Transaction */
                if ($transaction->loadByTransactionAndSpace($completion->getTransactionId(), $completion->getSpaceId())) {
                    $transaction->updateLineItems();
                    $this->send($completion);
                    if ($completion->getState() === self::getFailedState()) {
                        $errors[] = $completion->getFailureReason();
                    }
                } else {
                    $errors[] = TrustPaymentsModule::instance()->translate("Unable to load transaction !id in space !space", true, array('!id' => $completion->getTransactionId(), '!space' => $completion->getSpaceId()));
                }
            } else {
                TrustPaymentsModule::log(Logger::ERROR, "Unable to load pending job {$job['TRUJOBID']} / {$job['TRUSPACEID']}.");
            }
        }
        return $errors;
    }
}