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



use Tru\TrustPayments\Extend\Application\Model\Order;

/**
 * Class NavigationController.
 * Extends \order_list.
 *
 * @mixin \order_list
 */
class trutrustpayments_order_list extends trutrustpayments_order_list_parent
{
    protected $_sThisTemplate = 'truTrustPaymentsOrderList.tpl';

    public function render()
    {
        $orderId = $this->getEditObjectId();
        if ($orderId != '-1' && isset($orderId)) {
class_exists('oxorder');        	$order = oxNew('oxorder');
            $order->load($orderId);
            /* @var $order Order */

            if ($order->isTruOrder()) {
                $this->_aViewData['truEnabled'] = true;
            }
        }
        $this->_OrderList_render_parent();

        return $this->_sThisTemplate;
    }

    protected function _OrderList_render_parent()
    {
        return parent::render();
    }
}