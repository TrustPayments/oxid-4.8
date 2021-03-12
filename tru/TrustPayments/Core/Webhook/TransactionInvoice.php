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

use Monolog\Logger;
use TrustPayments\Sdk\Model\TransactionInvoiceState;
use TrustPayments\Sdk\Service\TransactionInvoiceService;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Extend\Application\Model\Order;

/**
 * Webhook processor to handle manual task state transitions.
 */
class TransactionInvoice extends AbstractOrderRelated
{
    /**
     * Loads and returns the entity for the webhook request.
     * @param Request $request
     * @return \TrustPayments\Sdk\Model\TransactionInvoice
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function loadEntity(Request $request)
    {
        $service = new TransactionInvoiceService(TrustPaymentsModule::instance()->getApiClient());
        return $service->read($request->getSpaceId(), $request->getEntityId());
    }

    /**
     * Loads and returns the order id associated with the given entity.
     *
     * @param object $entity
     * @return string
     * @throws \Exception
     */
    protected function getOrderId($entity)
    {
        /* @var $entity \TrustPayments\Sdk\Model\TransactionInvoice */
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        if ($transaction->loadByTransactionAndSpace($this->getTransactionId($entity), $entity->getLinkedSpaceId())) {
            return $transaction->getOrderId();
        }
        throw new \Exception("Could not load transaction {$entity->getLinkedTransaction()} in space {$entity->getLinkedSpaceId()}.");
    }

    /**
     * Returns the transaction id linked to the entity
     *
     *
     * @param object $entity
     * @return int
     */
    protected function getTransactionId($entity)
    {
        /* @var $entity \TrustPayments\Sdk\Model\TransactionInvoice */
        return $entity->getLinkedTransaction();
    }

    /**
     * Actually processes the order related webhook request.
     *
     * This must be implemented
     *
     * @param Order $order
     * @param object $entity
     */
    protected function processOrderRelatedInner(\oxorder $order, $entity)
    {
        /* @var $entity \TrustPayments\Sdk\Model\TransactionInvoice */
        switch ($entity->getState()) {
            case TransactionInvoiceState::NOT_APPLICABLE:
            case TransactionInvoiceState::PAID:
                $order->setTrustPaymentsPaid();
                return true;
            default:
                TrustPaymentsModule::log(Logger::WARNING, "Received unprocessable TransactionInvoiceState {$entity->getState()}.");
                return false;
        }
    }
}