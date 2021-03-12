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
use Tru\TrustPayments\Core\Service\RefundService;
use Tru\TrustPayments\Core\TrustPaymentsModule;


/**
 * Class RefundJob.
 */
class RefundJob extends \oxadminview
{
    /**
     * Controller template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'truTrustPaymentsRefundJob.tpl';

    /**
     * @return mixed|string
     */
    public function render()
    {
        $mReturn = $this->_RefundJob_render_parent();

class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        if ($transaction->loadByOrder($this->getEditObjectId())) {
            try {
                $transaction->pull();
                $this->_aViewData['lineItems'] = RefundService::instance()->getReducedItems($transaction);
                $this->_aViewData['oxTransactionId'] = $transaction->getId();
                return $mReturn;
            } catch (\Exception $e) {
                $error = TrustPaymentsModule::instance()->translate("Unable to load transaction for order !id.", true, array('!id' => $this->getEditObjectId()));
                $error .= ' ' . $e->getMessage() . ' - ' . $e->getTraceAsString();
            }
        } else {
            $error = TrustPaymentsModule::instance()->translate("Unable to load transaction for order !id.", true, array('!id' => $this->getEditObjectId()));
        }
        TrustPaymentsModule::log(Logger::ERROR, $error);
        $this->_aViewData['tru_error'] = $error;
        return 'truTrustPaymentsError.tpl';
    }

    public function refund()
    {
    	TrustPaymentsModule::log(Logger::DEBUG, "Start refund.");
class_exists(\Tru\TrustPayments\Application\Model\Transaction::class);        $transaction = oxNew(\Tru\TrustPayments\Application\Model\Transaction::class);
        /* @var $transaction \Tru\TrustPayments\Application\Model\Transaction */
        try {
        	if ($transaction->loadByOrder($this->getEditObjectId())) {
        		TrustPaymentsModule::log(Logger::DEBUG, "Loaded by order.");
        		$transaction->pull();
        		$job = RefundService::instance()->create($transaction, false);
        		TrustPaymentsModule::log(Logger::DEBUG, "Created job.");
                $job->setFormReductions(TrustPaymentsModule::instance()->getRequestParameter('item'));
                $job->setRestock(TrustPaymentsModule::instance()->getRequestParameter('restock') !== null);
                $job->save();
                TrustPaymentsModule::log(Logger::DEBUG, "Saved job.");
                RefundService::instance()->send($job);
                TrustPaymentsModule::log(Logger::DEBUG, "Sent job.");
            } else {
                TrustPaymentsModule::log(Logger::ERROR, "Unable to load transaction for order {$this->getEditObjectId()}.");
            }
        } catch (\Exception $e) {
            $refundId = "";
            if (isset($job)) {
                $refundId = " (" . $job->getId() . ")";
            }
            $message = "Unable to process refund $refundId for transaction {$transaction->getTransactionId()}. {$e->getMessage()} - {$e->getTraceAsString()}.";
            TrustPaymentsModule::log(Logger::ERROR, $message);
            TrustPaymentsModule::getUtilsView()->addErrorToDisplay($message);
        }

        \oxregistry::getUtils()->redirect(TrustPaymentsModule::getUtilsUrl()->cleanUrlParams(TrustPaymentsModule::getUtilsUrl()->appendUrl(TrustPaymentsModule::getUtilsUrl()->getCurrentUrl(), array('cl' => 'tru_trustPayments_Transaction', 'oxid' => $transaction->getOrderId(), 'cur' => $transaction->getOrderId())), '&'));
    }

    /**
     * Parent `render` call.
     * Method required for mocking.
     *
     * @codeCoverageIgnore
     *
     * @return mixed
     */
    protected function _RefundJob_render_parent()
    {
        return parent::render();
    }
}