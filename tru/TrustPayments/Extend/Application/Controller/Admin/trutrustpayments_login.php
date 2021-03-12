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

use Tru\TrustPayments\Application\Controller\Cron;

/**
 * Class BasketItem.
 * Extends \login.
 *
 * @mixin \login
 */
class trutrustpayments_login extends trutrustpayments_login_parent
{
    public function render()
    {
        $this->_aViewData['truCronUrl'] = Cron::getCronUrl();
        return $this->_NavigationController_render_parent();
    }

    protected function _NavigationController_render_parent()
    {
        return parent::render();
    }
}

