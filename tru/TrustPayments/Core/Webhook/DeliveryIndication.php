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

use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Webhook processor to handle delivery indication state transitions.
 */
class DeliveryIndication extends AbstractOrderRelated {

	/**
	 *
	 * @see AbstractOrderRelated::load_entity()
	 * @return \TrustPayments\Sdk\Model\DeliveryIndication
	 */
	protected function loadEntity(Request $request){
		$service = new \TrustPayments\Sdk\Service\DeliveryIndicationService(TrustPaymentsModule::instance()->getApiClient());
		return $service->read($request->getSpaceId(), $request->getEntityId());
	}

	protected function getOrderId($deliveryIndication){
		/* @var \TrustPayments\Sdk\Model\DeliveryIndication $delivery_indication */
		return $deliveryIndication->getTransaction()->getMerchantReference();
	}

	protected function getTransactionId($deliveryIndication){
		/* @var $delivery_indication \TrustPayments\Sdk\Model\DeliveryIndication */
		return $deliveryIndication->getLinkedTransaction();
	}

	protected function processOrderRelatedInner(\oxorder $order, $deliveryIndication){
		/* @var \TrustPayments\Sdk\Model\DeliveryIndication $deliveryIndication */
		switch ($deliveryIndication->getState()) {
			case \TrustPayments\Sdk\Model\DeliveryIndicationState::MANUAL_CHECK_REQUIRED:
				$this->review($order);
				break;
			default:
				// Nothing to do.
				break;
		}
	}

	protected function review(\oxorder $order){
		$order->getTrustPaymentsTransaction()->pull();
		$order->setTrustPaymentsState($order->getTrustPaymentsTransaction()->getState());
	}
}