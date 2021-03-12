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

require_once(OX_BASE_PATH . "modules/tru/TrustPayments/autoload.php");

/**
 * Class Webhook.
 */
class tru_trustPayments_Webhook extends \Tru\TrustPayments\Application\Controller\Webhook
{}