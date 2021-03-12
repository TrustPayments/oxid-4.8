<?php
/**
 * TrustPayments OXID
 *
 * This OXID module enables to process payments with TrustPayments (https://www.trustpayments.com//).
 *
 * @package Whitelabelshortcut\TrustPayments
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */require_once(OX_BASE_PATH . "modules/tru/TrustPayments/autoload.php");




use Tru\TrustPayments\Application\Model\Transaction;
use Tru\TrustPayments\Core\Service\PaymentService;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Monolog\Logger;

/**
 * Class PaymentList.
 * Extends \oxpaymentlist.
 *
 * @mixin \oxpaymentlist
 */
class trutrustpayments_payment_list extends trutrustpayments_payment_list_parent
{

    /**
     * Loads all TrustPayments payment methods.
     */
    public function loadTrustPaymentsPayments()
    {
        $prefix = TrustPaymentsModule::PAYMENT_PREFIX;
        $this->selectString("SELECT * FROM `oxpayments` WHERE `oxid` LIKE '$prefix%'");
        return $this->_aArray;
    }

    /**
     * Loads all TrustPayments payment methods.
     */
    public function loadActiveTrustPaymentsPayments()
    {
        $prefix = TrustPaymentsModule::PAYMENT_PREFIX;
        $this->selectString("SELECT * FROM `oxpayments` WHERE `oxid` LIKE '$prefix%' AND `oxactive` = '1'");
        return $this->_aArray;
    }

    public function getPaymentList($sShipSetId, $dPrice, $oUser = null)
    {
        $oxPayments = $this->_PaymentList_getPaymentList_parent($sShipSetId, $dPrice, $oUser);
        if(!$this->isAdmin()) {
            $this->clear();
            $TrustPaymentsPayments = array();
            try {
                $transaction = Transaction::loadPendingFromSession($this->getSession());
                $TrustPaymentsPayments = PaymentService::instance()->fetchAvailablePaymentMethods($transaction->getTransactionId(), $transaction->getSpaceId());
            } catch (\Exception $e) {
                TrustPaymentsModule::log(Logger::ERROR, $e->getMessage(), array($this, $e));
            }
            foreach ($oxPayments as $oxPayment) {
                /* @var $oxPayment \oxpayment */
                if (TrustPaymentsModule::isTrustPaymentsPayment($oxPayment->getId())) {
                    if (in_array($oxPayment->getId(), $TrustPaymentsPayments)) {
                        $this->_aArray[$oxPayment->getId()] = $oxPayment;
                    }
                } else {
                    $this->_aArray[$oxPayment->getId()] = $oxPayment;
                }
            }
        }
        return $this->_aArray;
    }

    protected function _PaymentList_getPaymentList_parent($sShipSetId, $dPrice, $oUser = null)
    {
        return parent::getPaymentList($sShipSetId, $dPrice, $oUser);
    }
}