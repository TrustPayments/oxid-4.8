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
namespace Tru\TrustPayments\Core\Service;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use TrustPayments\Sdk\Model\ManualTaskState;
use TrustPayments\Sdk\Service\ManualTaskService;
use Tru\TrustPayments\Application\Model\Alert;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * This service provides methods to handle manual tasks.
 */
class ManualTask extends AbstractService
{
    /**
     * Updates the number of open manual tasks.
     *
     * @throws \Exception
     * @return int
     */
    public function update()
    {
        try {
            $service = new ManualTaskService(TrustPaymentsModule::instance()->getApiClient());

            $taskCount = $service->count(TrustPaymentsModule::settings()->getSpaceId(),
                $this->createEntityFilter('state', ManualTaskState::OPEN));

            Alert::setCount(Alert::KEY_MANUAL_TASK, $taskCount);

            return $taskCount;
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Unable to update manual tasks: {$e->getMessage()} - {$e->getTraceAsString()}.");
            throw $e;
        }
    }
}