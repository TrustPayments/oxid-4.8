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
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Core\Exception\OptimisticLockingException;

/**
 * Abstract webhook processor for order related entities.
 */
abstract class AbstractOrderRelated extends AbstractWebhook
{
	const NO_ORDER = 1;
	const OPTIMISTIC_RETRIES = 6;
	const SECONDS_TO_WAIT = 1;

    /**
     * Processes the received order related webhook request.
     * @param Request $request
     * @throws \Exception
     */
    public function process(Request $request)
    {
        if ($request->getSpaceId() != TrustPaymentsModule::settings()->getSpaceId()) {
            throw new \Exception("Received webhook with space id {$request->getSpaceId()} in store which is configured for space id " . TrustPaymentsModule::settings()->getSpaceId());
        }
        for($i = 0; $i <= self::OPTIMISTIC_RETRIES; $i++) {
	        try {
	            $entity = $this->loadEntity($request);
	            $orderId = $this->getOrderId($entity);
	            $order = $this->loadOrder($orderId);
	            \oxregistry::getLang()->setBaseLanguage($order->getOrderLanguage());
	            
	            if(!$order->getTrustPaymentsTransaction() || !$order->getTrustPaymentsTransaction()->getId()){
	            	throw new \Exception("Transaction could not be loaded on order.");
	            }
	            
	            if($this->processOrderRelatedInner($order, $entity)) {
		            if(!$order->getTrustPaymentsTransaction()->save() || !$order->save()) {
		            	throw new \Exception('Unable to save order');
		            }
	            }
	            
	        }catch(OptimisticLockingException $e){
	        	TrustPaymentsModule::log(Logger::WARNING, "Optimistic locking query: " . $e->getQueryString());
	        	TrustPaymentsModule::rollback();
	        	if($i === self::OPTIMISTIC_RETRIES) {
	        		throw $e;
	        	}
	        	sleep(self::SECONDS_TO_WAIT);
	        }
	        catch (\Exception $e) {
	            TrustPaymentsModule::log(Logger::ERROR, $e->getMessage() . ' - ' . $e->getTraceAsString());
	            TrustPaymentsModule::rollback();
	            if($e->getCode() !== self::NO_ORDER) {
	            	throw $e;
	            }
        	}
        }
    }


    /**
     * @param $orderId
     * @return \oxorder
     * @throws \Exception
     */
    protected function loadOrder($orderId)
    {
    	TrustPaymentsModule::getUtilsObject()->resetInstanceCache('oxorder');
class_exists('oxorder');        $order = oxNew('oxorder');
        /* @var $order \Tru\TrustPayments\Extend\Application\Model\Order */
        if ($order->load($orderId) && $order->isTruOrder()) {
            return $order;
        }
        throw new \Exception("Could not load order by id $orderId.", self::NO_ORDER);
    }

    /**
     * Loads and returns the entity for the webhook request.
     *
     * @param Request $request
     * @return object
     */
    abstract protected function loadEntity(Request $request);

    /**
     * Returns the order id linked to the entity.
     *
     * @param object $entity
     * @return string
     */
    abstract protected function getOrderId($entity);

    /**
     * Returns the transaction id linked to the entity
     *
     *
     * @param object $entity
     * @return int
     */
    abstract protected function getTransactionId($entity);

    /**
     * Actually processes the order related webhook request.
     *
     * This must be implemented
     *
     * @param \oxorder $order
     * @param object $entity
     * @return bool If a change was applied to the database, e.g. if the order should be saved.
     */
    abstract protected function processOrderRelatedInner(\oxorder $order, $entity);
}