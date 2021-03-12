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
 * Class RefundJob.
 */
class tru_trustPayments_RefundJob extends \Tru\TrustPayments\Application\Controller\Admin\RefundJob
{
}