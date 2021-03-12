<?php
/**
 * TrustPayments OXID
 *
 * This OXID module enables to process payments with TrustPayments (https://www.trustpayments.com//).
 *
 * @package Whitelabelshortcut\TrustPayments
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */require_once(OX_BASE_PATH . "modules/tru/TrustPayments/autoload.php");

use Monolog\Logger;
use Tru\TrustPayments\Core\TrustPaymentsModule;
use Tru\TrustPayments\Core\Service\PaymentService;
use Tru\TrustPayments\Core\Webhook\Service as WebhookService;

/**
 * Class BasketItem.
 * Extends \module_config.
 *
 * @mixin \module_config
 */
class trutrustpayments_module_config extends trutrustpayments_module_config_parent
{

    public function init()
    {
        if ($this->getEditObjectId() == TrustPaymentsModule::instance()->getId() && $this->getFncName() !== 'saveConfVars') {
            // if plugin was inactive before and has settings changed (which we cannot interfere with as extensions are inactive) - force global parameters over current local settings.
            TrustPaymentsModule::settings()->setGlobalParameters($this->getConfig()->getBaseShopId());
        }
        $this->_ModuleConfiguration_init_parent();
    }

    protected function _ModuleConfiguration_init_parent()
    {
        parent::init();
    }

    public function saveConfVars()
    {
        $this->_ModuleConfiguration_saveConfVars_parent();
        if ($this->getEditObjectId() == TrustPaymentsModule::instance()->getId()) {
            try {
            	TrustPaymentsModule::settings()->setGlobalParameters();
            	TrustPaymentsModule::addMessage(TrustPaymentsModule::instance()->translate("Settings saved successfully."));
                // force api client refresh
                TrustPaymentsModule::instance()->getApiClient(true);

                $paymentService = new PaymentService();
                $paymentService->synchronize();
                TrustPaymentsModule::addMessage(TrustPaymentsModule::instance()->translate("Payment methods successfully synchronized."));

                $oldUrl = TrustPaymentsModule::settings()->getWebhookUrl();
                $newUrl = TrustPaymentsModule::instance()->createWebhookUrl();
                if ($oldUrl !== $newUrl) {
                    $webhookService = new WebhookService();
                    $webhookService->uninstall(TrustPaymentsModule::settings()->getSpaceId(), $oldUrl);;
                    $webhookService->install(TrustPaymentsModule::settings()->getSpaceId(), $newUrl);
                    TrustPaymentsModule::settings()->setWebhookUrl($newUrl);
                    TrustPaymentsModule::addMessage(TrustPaymentsModule::instance()->translate("Webhook URL updated successfully."));
                }
            } catch (\Exception $e) {
                TrustPaymentsModule::log(Logger::ERROR, "Unable to synchronize settings: {$e->getMessage()}.");
                TrustPaymentsModule::getUtilsView()->addErrorToDisplay($e->getMessage());
            }
        }
    }

    protected function _ModuleConfiguration_saveConfVars_parent()
    {
        parent::saveConfVars();
    }
}

