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

namespace Tru\TrustPayments\Application\Model;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Tru\TrustPayments\Core\Service\CompletionService;
use Tru\TrustPayments\Core\Service\JobService;

/**
 * Class CompletionJob.
 * CompletionJob model.
 */
class CompletionJob extends AbstractJob
{
    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->init('truTrustPayments_completionjob');
    }

    /**
     * @return JobService
     */
    protected function getService()
    {
        return CompletionService::instance();
    }
}