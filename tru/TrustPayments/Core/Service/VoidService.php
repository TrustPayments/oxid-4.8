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
use TrustPayments\Sdk\Service\TransactionVoidService;
use Tru\TrustPayments\Application\Model\AbstractJob;
use Tru\TrustPayments\Application\Model\VoidJob;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class VoidService
 */
class VoidService extends JobService
{
    private $service;

    protected function getService()
    {
        if ($this->service === null) {
            $this->service = new TransactionVoidService(TrustPaymentsModule::instance()->getApiClient());
        }
        return $this->service;
    }


    protected function getJobType()
    {
        return VoidJob::class;
    }

    public function getSupportedTransactionStates()
    {
        return array(
            TransactionState::AUTHORIZED
        );
    }

    protected function processSend(AbstractJob $job)
    {
        if (!$job instanceof VoidJob) {
            throw new \Exception("Invalid job type supplied.");
        }
        return $this->getService()->voidOnline($job->getSpaceId(), $job->getTransactionId());
    }

    public function resendAll()
    {
        $errors = array();
class_exists(VoidJob::class);        $void = oxNew(VoidJob::class);
        /* @var $void \Tru\TrustPayments\Application\Model\VoidJob */
        $notSent = $void->loadNotSentIds();
        foreach ($notSent as $job) {
            if ($void->loadByJob($job['TRUJOBID'], $job['TRUSPACEID'])) {
                $this->send($void);
                if ($void->getState() === self::getFailedState()) {
                    $errors[] = $void->getFailureReason();
                }
            } else {
                TrustPaymentsModule::log(Logger::ERROR, "Unable to load pending job {$job['TRUJOBID']} / {$job['TRUSPACEID']}.");
            }
        }
        return $errors;
    }
}