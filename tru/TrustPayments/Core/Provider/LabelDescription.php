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

use TrustPayments\Sdk\Service\LabelDescriptionService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Provider of label descriptor information from the gateway.
 */
class LabelDescription extends AbstractProvider
{

    protected function __construct()
    {
        parent::__construct('ox_TrustPayments_label_descriptor');
    }

    /**
     * Returns the label descriptor by the given code.
     *
     * @param int $id
     * @return \TrustPayments\Sdk\Model\LabelDescriptor
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * Returns a list of label descriptors.
     *
     * @return \TrustPayments\Sdk\Model\LabelDescriptor[]
     */
    public function getAll()
    {
        return parent::getAll();
    }

    /**
     * @return array|\TrustPayments\Sdk\Model\LabelDescriptor[]
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function fetchData()
    {
        $service = new LabelDescriptionService(TrustPaymentsModule::instance()->getApiClient());
        return $service->all();
    }

    protected function getId($entry)
    {
        /* @var \TrustPayments\Sdk\Model\LabelDescriptor $entry */
        return $entry->getId();
    }
}