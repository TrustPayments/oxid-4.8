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
namespace Tru\TrustPayments\Core\Webhook;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use TrustPayments\Sdk\Model\TransactionCompletionState;
use TrustPayments\Sdk\Service\TransactionCompletionService;
use Tru\TrustPayments\Application\Model\CompletionJob;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Extend\Application\Model\Order;

/**
 * Webhook processor to handle transaction completion state transitions.
 */
class TransactionCompletion extends AbstractOrderRelated
{

    /**
     * @param Request $request
     * @return \TrustPayments\Sdk\Model\TransactionCompletion
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function loadEntity(Request $request)
    {
        $service = new TransactionCompletionService(TrustPaymentsModule::instance()->getApiClient());
        return $service->read($request->getSpaceId(), $request->getEntityId());
    }

    /**
     * @param object $completion
     * @return string
     * @throws \Exception
     */
    protected function getOrderId($completion)
    {
        /* @var \TrustPayments\Sdk\Model\TransactionCompletion $completion */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        if ($transaction->loadByTransactionAndSpace($completion->getLinkedTransaction(), $completion->getLinkedSpaceId())) {
            return $transaction->getOrderId();
        }
        throw new \Exception("Unable to load transaction {$completion->getLinkedTransaction()} in space {$completion->getLinkedSpaceId()} from database.");
    }

    protected function getTransactionId($entity)
    {
        /* @var $entity \TrustPayments\Sdk\Model\TransactionCompletion */
        return $entity->getLinkedTransaction();
    }

    /**
     * @param Order $order
     * @param object $completion
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function processOrderRelatedInner(\oxorder $order, $completion)
    {
        /* @var \TrustPayments\Sdk\Model\TransactionCompletion $completion */
        switch ($completion->getState()) {
            case TransactionCompletionState::FAILED:
                $this->failed($completion, $order);
                return true;
            case TransactionCompletionState::SUCCESSFUL:
                $this->success($completion, $order);
                return true;
            default:
                // Ignore PENDING & CREATE
                // Nothing to do.
                return false;
        }
    }

    /**
     * @param \TrustPayments\Sdk\Model\TransactionCompletion $completion
     * @param Order $order
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function success(\TrustPayments\Sdk\Model\TransactionCompletion $completion, \oxorder $order)
    {
class_exists(\Tru\TrustPayments\Application\Model\CompletionJob::class);    	$job = oxNew(\Tru\TrustPayments\Application\Model\CompletionJob::class);
        /* @var $job CompletionJob */
        if ($job->loadByOrder($order->getId()) || $job->loadByJob($completion->getId(), $completion->getLinkedSpaceId())) {
            $job->apply($completion);
        }
        $order->getTrustPaymentsTransaction()->pull();
        $order->setTrustPaymentsState($order->getTrustPaymentsTransaction()->getState());
    }

    /**
     * Fails the given order.
     *
     * @param \TrustPayments\Sdk\Model\TransactionCompletion $completion
     * @param Order $order
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function failed(\TrustPayments\Sdk\Model\TransactionCompletion $completion, \oxorder $order)
    {
        /** @noinspection PhpParamsInspection */
        $message = TrustPaymentsModule::instance()->TrustPaymentsTranslate($completion->getFailureReason()->getName());
        /** @noinspection PhpParamsInspection */
        $message .= TrustPaymentsModule::instance()->TrustPaymentsTranslate($completion->getFailureReason()->getDescription());
        $order->getTrustPaymentsTransaction()->pull();
        $order->TrustPaymentsFail($message, $order->getTrustPaymentsTransaction()->getState(), true, true);

class_exists(\Tru\TrustPayments\Application\Model\CompletionJob::class);        $job = oxNew(\Tru\TrustPayments\Application\Model\CompletionJob::class);
        /* @var $job \Tru\TrustPayments\Application\Model\CompletionJob */
        if ($job->loadByJob($completion->getId(), $completion->getLinkedSpaceId()) || $job->loadByOrder($order->getId())) {
            $job->apply($completion);
        }
    }
}