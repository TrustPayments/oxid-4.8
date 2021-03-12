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

use TrustPayments\Sdk\Service\CurrencyService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Provider of currency information from the gateway.
 */
class Currency extends AbstractProvider
{

    protected function __construct()
    {
        parent::__construct('ox_TrustPayments_currency');
    }

    /**
     * Returns the currency by the given code.
     *
     * @param string $code
     * @return \TrustPayments\Sdk\Model\RestCurrency
     */
    public function find($code)
    {
        return parent::find($code);
    }

    /**
     * Returns a list of currencies.
     *
     * @return \TrustPayments\Sdk\Model\RestCurrency[]
     */
    public function getAll()
    {
        return parent::getAll();
    }

    /**
     * @return array|\TrustPayments\Sdk\Model\RestCurrency[]
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function fetchData()
    {
        $service = new CurrencyService(TrustPaymentsModule::instance()->getApiClient());
        return $service->all();
    }

    protected function getId($entry)
    {
        /* @var \TrustPayments\Sdk\Model\RestCurrency $entry */
        return $entry->getCurrencyCode();
    }
}