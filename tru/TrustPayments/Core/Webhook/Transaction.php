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
use TrustPayments\Sdk\Model\TransactionState;
use TrustPayments\Sdk\Service\TransactionService;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Extend\Application\Model\Order;

/**
 * Webhook processor to handle transaction state transitions.
 */
class Transaction extends AbstractOrderRelated
{
    /**
     * Retrieves the entity from TrustPayments via sdk.
     *
     * @param Request $request
     * @return \TrustPayments\Sdk\Model\Transaction
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function loadEntity(Request $request)
    {
        $service = new TransactionService(TrustPaymentsModule::instance()->getApiClient());
        return $service->read($request->getSpaceId(), $request->getEntityId());
    }

    protected function getOrderId($transaction)
    {
        /* @var \TrustPayments\Sdk\Model\Transaction $transaction */

class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $dbTransaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $dbTransaction \Tru\TrustPayments\Application\Model\Transaction */
        $dbTransaction->loadByTransactionAndSpace($transaction->getId(), $transaction->getLinkedSpaceId());
        return $dbTransaction->getOrderId();
    }

    protected function getTransactionId($transaction)
    {
        /* @var \TrustPayments\Sdk\Model\Transaction $transaction */
        return $transaction->getId();
    }

    /**
     * @param Order $order
     * @param object $entity
     * @throws \Exception
     */
    protected function processOrderRelatedInner(\oxorder $order, $entity)
    {
        $finalStates = [
            TransactionState::FAILED,
            TransactionState::VOIDED,
            TransactionState::DECLINE,
            TransactionState::FULFILL
        ];

        /* @var $entity \TrustPayments\Sdk\Model\Transaction */
        if (in_array($entity->getState(), $finalStates)) {
            return false;
        }

        /* @var $order \Tru\TrustPayments\Extend\Application\Model\Order */
        if ($entity && $entity->getState() !== $order->getTrustPaymentsTransaction()->getState()) {
            $cancel = false;
            switch ($entity->getState()) {
                case TransactionState::AUTHORIZED:
                case TransactionState::FULFILL:
                case TransactionState::COMPLETED:
                    $oldState = $order->getFieldData('oxtransstatus');
                    $order->setTrustPaymentsState($entity->getState());
                    if (!TrustPaymentsModule::isAuthorizedState($oldState)) {
                        $order->TrustPaymentsAuthorize();
                    }
                    return true;
                case TransactionState::CONFIRMED:
                case TransactionState::PROCESSING:
                	$order->setTrustPaymentsState($entity->getState());
                	return true;
                case TransactionState::VOIDED:
                    $cancel = true;
                case TransactionState::DECLINE:
                case TransactionState::FAILED:
                	$order->setTrustPaymentsState($entity->getState());
                	$order->TrustPaymentsFail($entity->getUserFailureMessage(), $entity->getState(), $cancel, true);
                	return true;
                default:
                	return false;
            }
        }
        return false;
    }
}