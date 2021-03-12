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
use Tru\TrustPayments\Core\Service\PaymentService;

/**
 * Webhook processor to handle payment method configuration state transitions.
 */
class MethodConfiguration extends AbstractWebhook
{

    /**
     * Synchronizes the payment method configurations on state transition.
     * @param Request $request
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    public function process(Request $request)
    {
        $paymentService = new PaymentService();
        $paymentService->synchronize();
    }
}