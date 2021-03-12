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

namespace Tru\TrustPayments\Core;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;

/**
 * Class Settings
 * Handles access to module settings.
 *
 * @codeCoverageIgnore
 */
class Settings {

    public function getLogFile(){
        return OX_BASE_PATH . DIRECTORY_SEPARATOR . "log" . '/TrustPayments.log';
    }
    public function getCommunicationsLog(){
        return OX_BASE_PATH . DIRECTORY_SEPARATOR . "log" . '/TrustPayments_communication.log';
    }

	public function getBaseUrl(){
		return 'https://app-wallee.com';
	}

	public function getSpaceId(){
		return $this->getSetting('SpaceId');
	}

	public function getSpaceViewId(){
		return $this->getSetting('SpaceViewId');
	}

	public function isDownloadInvoiceEnabled(){
		return $this->getSetting('InvoiceDoc');
	}

	public function isDownloadPackingEnabled(){
		return $this->getSetting('PackingDoc');
	}
	
	public function enforceLineItemConsistency() {
		return $this->getSetting('EnforceConsistency');
	}

	public function isEmailConfirmationActive() {
	    return $this->getSetting('EmailConfirm');
    }

	public function isLogCommunications(){
		return $this->getLogLevel() === 'DEBUG';
	}

	public function getMappedLogLevel(){
		switch ($this->getLogLevel()) {
			case 'ERROR':
				// ERROR, CRITICAL, ALERT, EMERGENCY
				return Logger::ERROR;
			case 'DEBUG':
				// DEBUG
				return Logger::DEBUG;
			case 'INFO':
				// INFO, NOTICE, WARNING
				return Logger::WARNING;
			default:
				return Logger::WARNING;
		}
	}

	public function getUserId(){
		return \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsUserId', \oxregistry::getConfig()->getBaseShopId(),\oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
	}

	public function getAppKey(){
		return \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsAppKey', \oxregistry::getConfig()->getBaseShopId(),\oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
	}

	public function getMigration() {
		$level = \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsMigration', \oxregistry::getConfig()->getBaseShopId(),\oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
		if(!$level) {
            $level = 0;
        }
        return $level;
    }

    public function setMigration($level) {
    	\oxregistry::getConfig()->saveShopConfVar('num', 'truTrustPaymentsMigration', $level, \oxregistry::getConfig()->getBaseShopId(), \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    }

	protected function getLogLevel(){
		return strtoupper($this->getSetting('LogLevel'));
	}

	/**
	 * Get module setting value.
	 *
	 * @param string $sModuleSettingName Module setting parameter name (key).
	 * @param boolean $blUseModulePrefix If True - adds the module settings prefix, if False - not.
	 *
	 * @return mixed
	 */
	protected function getSetting($sModuleSettingName, $blUseModulePrefix = true){
		if ($blUseModulePrefix) {
			$sModuleSettingName = 'truTrustPayments' . (string) $sModuleSettingName;
		}
		return \oxregistry::getConfig()->getConfigParam((string) $sModuleSettingName);
	}

	protected function setSetting($value, $sModuleSettingName, $blUseModulePrefix = true){
        if ($blUseModulePrefix) {
            $sModuleSettingName = 'truTrustPayments' . (string) $sModuleSettingName;
        }
        \oxregistry::getConfig()->setConfigParam((string) $sModuleSettingName, $value);
    }

	public function getWebhookUrl() {
		return \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsWebhook', \oxregistry::getConfig()->getBaseShopId(),\oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    }

    public function setWebhookUrl($value) {
    	\oxregistry::getConfig()->saveShopConfVar('string', 'truTrustPaymentsWebhook', $value, \oxregistry::getConfig()->getBaseShopId(), \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    }

    public function setGlobalParameters($shopId = null) {
    	$appKey = \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsAppKey', $shopId, \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    	$userId = \oxregistry::getConfig()->getShopConfVar('truTrustPaymentsUserId', $shopId, \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    	foreach(\oxregistry::getConfig()->getShopIds() as $shop) {
	    	\oxregistry::getConfig()->saveShopConfVar('str', 'truTrustPaymentsAppKey', $appKey, $shop, \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
	    	\oxregistry::getConfig()->saveShopConfVar('str', 'truTrustPaymentsUserId', $userId, $shop, \oxconfig::OXMODULE_MODULE_PREFIX . TrustPaymentsModule::instance()->getId());
    	}
    }
}