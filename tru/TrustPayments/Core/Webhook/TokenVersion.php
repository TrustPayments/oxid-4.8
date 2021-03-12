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

use Tru\TrustPayments\Core\Service\Token as TokenService;

/**
 * Webhook processor to handle token version state transitions.
 */
class TokenVersion extends AbstractWebhook
{

    public function process(Request $request)
    {
        TokenService::instance()->updateTokenVersion($request->getSpaceId(), $request->getEntityId());
    }
}