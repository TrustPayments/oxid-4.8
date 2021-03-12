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

use TrustPayments\Sdk\Service\TransactionVoidService;
use Tru\TrustPayments\Application\Model\VoidJob;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Extend\Application\Model\Order;
use TrustPayments\Sdk\Model\TransactionVoidState;
use Monolog\Logger;

/**
 * Webhook processor to handle transaction void state transitions.
 */
class TransactionVoid extends AbstractOrderRelated
{

    /**
     * @param Request $request
     * @return \TrustPayments\Sdk\Model\TransactionVoid
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function loadEntity(Request $request)
    {
        $voidService = new TransactionVoidService(TrustPaymentsModule::instance()->getApiClient());
        return $voidService->read($request->getSpaceId(), $request->getEntityId());
    }

    protected function getOrderId($void)
    {
        /* @var \TrustPayments\Sdk\Model\TransactionVoid $void */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $dbTransaction \Tru\TrustPayments\Application\Model\Transaction */
        $transaction->loadByTransactionAndSpace($void->getTransaction()->getId(), $void->getLinkedSpaceId());
        return $transaction->getOrderId();
    }

    protected function getTransactionId($entity)
    {
        /* @var $entity \TrustPayments\Sdk\Model\TransactionVoid */
        return $entity->getTransaction()->getId();
    }

    protected function processOrderRelatedInner(\oxorder $order, $void)
    {
        /* @var \TrustPayments\Sdk\Model\TransactionVoid $void */
        if ($this->apply($void, $order)) {
            switch ($void->getState()) {
                case TransactionVoidState::SUCCESSFUL:
                    $order->cancelOrder();
                    return true;
                default:
                    // Nothing to do.
                    break;
            }
        }
        return false;
    }

    protected function apply(\TrustPayments\Sdk\Model\TransactionVoid $void, Order $order)
    {
class_exists(\Tru\TrustPayments\Application\Model\VoidJob::class);    	$job = oxNew(\Tru\TrustPayments\Application\Model\VoidJob::class);
        /* @var $job \Tru\TrustPayments\Application\Model\VoidJob */
        if ($job->loadByJob($void->getId(), $void->getLinkedSpaceId()) || $job->loadByOrder($order->getId())) {
            if ($job->getState() !== $void->getState()) {
                $job->apply($void);
                return true;
            }
        } else {
            TrustPaymentsModule::log(Logger::WARNING, "Unknown void received, was not processed: $void.");
        }
        return false;
    }
}