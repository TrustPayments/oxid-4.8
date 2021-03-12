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



use Monolog\Logger;
use Tru\TrustPayments\Application\Model\Alert;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class NavigationController.
 * Extends \navigation.
 *
 * @mixin \oxadminview
 */
class trutrustpayments_navigation extends trutrustpayments_navigation_parent
{
	public function render() {
		$result = parent::render();
		if($result === 'header.tpl') {
			return 'truTrustPaymentsHeader.tpl';
		}
		return $result;
	}
	
	public function getTruAlerts()
    {
        $alerts = array();
        foreach (Alert::loadAll() as $row) {
            if ($row[1] > 0) {
                switch ($row[0]) {
                    case Alert::KEY_MANUAL_TASK:
                        $alerts[] = array(
                            'func' => $row[2],
                            'target' => $row[3],
                            'title' => TrustPaymentsModule::instance()->translate("Manual Tasks (!count)", true, array('!count' => $row[1]))
                        );
                        break;
                    default:
                        TrustPaymentsModule::log(Logger::WARNING, "Unkown alert loaded from database: " . array($row));
                }
            }
        }
        return $alerts;
    }
}

