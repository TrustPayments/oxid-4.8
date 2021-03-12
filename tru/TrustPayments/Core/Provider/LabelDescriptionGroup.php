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
namespace Tru\TrustPayments\Core\Provider;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use TrustPayments\Sdk\Service\LabelDescriptionGroupService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Provider of label descriptor group information from the gateway.
 */
class LabelDescriptionGroup extends AbstractProvider
{

    protected function __construct()
    {
        parent::__construct('ox_TrustPayments_label_descriptor_group');
    }

    /**
     * Returns the label descriptor group by the given code.
     *
     * @param int $id
     * @return \TrustPayments\Sdk\Model\LabelDescriptorGroup
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * Returns a list of label descriptor groups.
     *
     * @return \TrustPayments\Sdk\Model\LabelDescriptorGroup[]
     */
    public function getAll()
    {
        return parent::getAll();
    }

    /**
     * @return array|\TrustPayments\Sdk\Model\LabelDescriptorGroup[]
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function fetchData()
    {
        $service = new LabelDescriptionGroupService(TrustPaymentsModule::instance()->getApiClient());
        return $service->all();
    }

    protected function getId($entry)
    {
        /* @var \TrustPayments\Sdk\Model\LabelDescriptorGroup $entry */
        return $entry->getId();
    }
}