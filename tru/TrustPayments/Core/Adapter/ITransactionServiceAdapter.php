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

namespace Tru\TrustPayments\Core\Adapter;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use \Tru\TrustPayments\Application\Model\Transaction;

/**
 * Interface ITransactionServiceAdapter
 * Defines which methods must be implemented to be consumed with TrustPayments SDK.
 *
 * @codeCoverageIgnore
 */
interface ITransactionServiceAdapter {

	function getCreateData();

	function getUpdateData(Transaction $transaction);
}