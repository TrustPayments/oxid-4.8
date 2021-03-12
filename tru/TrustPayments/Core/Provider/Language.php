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

use TrustPayments\Sdk\Service\LanguageService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Provider of language information from the gateway.
 */
class Language extends AbstractProvider
{

    protected function __construct()
    {
        parent::__construct('ox_TrustPayments_languages');
    }

    /**
     * Returns the language by the given code.
     *
     * @param string $code
     * @return \TrustPayments\Sdk\Model\RestLanguage
     */
    public function find($code)
    {
        return parent::find($code);
    }

    /**
     * Returns the primary language in the given group.
     *
     * @param string $code
     * @return bool|\TrustPayments\Sdk\Model\RestLanguage
     */
    public function findPrimary($code)
    {
        $code = substr($code, 0, 2);
        foreach ($this->getAll() as $language) {
            if ($language->getIso2Code() == $code && $language->getPrimaryOfGroup()) {
                return $language;
            }
        }

        return false;
    }

    /**
     * @param $iso
     * @return bool|\TrustPayments\Sdk\Model\RestLanguage
     */
    public function findByIsoCode($iso)
    {
        foreach ($this->getAll() as $language) {
            if ($language->getIso2Code() == $iso || $language->getIso3Code() == $iso) {
                return $language;
            }
        }
        return false;
    }

    /**
     * Returns a list of language.
     *
     * @return \TrustPayments\Sdk\Model\RestLanguage[]
     */
    public function getAll()
    {
        return parent::getAll();
    }

    /**
     * @return array|\TrustPayments\Sdk\Model\RestLanguage[]
     * @throws \TrustPayments\Sdk\ApiException
     */
    protected function fetchData()
    {
        $service = new LanguageService(TrustPaymentsModule::instance()->getApiClient());
        return $service->all();
    }

    protected function getId($entry)
    {
        /* @var \TrustPayments\Sdk\Model\RestLanguage $entry */
        return $entry->getIetfCode();
    }
}