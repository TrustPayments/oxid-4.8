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

namespace Tru\TrustPayments\Application\Controller;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use Tru\TrustPayments\Core\Service\CompletionService;
use Tru\TrustPayments\Core\Service\RefundService;
use Tru\TrustPayments\Core\Service\VoidService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class Cron.
 */
class Cron extends \oxview
{
    public function init()
    {
        $this->_Cron_init_parent();
        $this->endRequestPrematurely();

        $oxid = TrustPaymentsModule::instance()->getRequestParameter('oxid');
        if (!$oxid) {
            TrustPaymentsModule::log(Logger::WARNING, 'CRON called without id.');
            exit();
        }

        try {
            \oxdb::getDb()->startTransaction();
            $result = \Tru\TrustPayments\Application\Model\Cron::setProcessing($oxid);
            \oxdb::getDb()->commitTransaction();
            if (!$result) {
                exit();
            }
        } catch (\Exception $e) {
            TrustPaymentsModule::log(Logger::ERROR, "Updating cron failed: {$e->getMessage()}.");
            TrustPaymentsModule::rollback();
            exit();
        }

        $errors = array_merge(
            CompletionService::instance()->resendAll(),
            VoidService::instance()->resendAll(),
            RefundService::instance()->resendAll()
        );

        try {
            \oxdb::getDb()->startTransaction();
            $result = \Tru\TrustPayments\Application\Model\Cron::setComplete($oxid, implode('. ', $errors));
            \Tru\TrustPayments\Application\Model\Cron::insertNewPendingCron();
            \oxdb::getDb()->commitTransaction();
            if (!$result) {
                TrustPaymentsModule::log(Logger::ERROR, "Could not update finished cron job.");
                exit();
            }
        } catch (\Exception $e) {
            TrustPaymentsModule::rollback();
            TrustPaymentsModule::log(Logger::ERROR, "Could not update finished cron job.");
            exit();
        }
        exit();
    }

    private function endRequestPrematurely()
    {
        ob_end_clean();
        // Return request but keep executing
        set_time_limit(0);
        ignore_user_abort(true);
        ob_start();
        if (session_id()) {
            session_write_close();
        }
        header("Content-Encoding: none");
        header("Connection: close");
        header('Content-Type: text/javascript');
        ob_end_flush();
        flush();
        if (is_callable('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

    protected function _Cron_init_parent()
    {
        return parent::init();
    }

    public static function getCronUrl()
    {
        \Tru\TrustPayments\Application\Model\Cron::cleanUpHangingCrons();
        \Tru\TrustPayments\Application\Model\Cron::insertNewPendingCron();
        $oxid = \Tru\TrustPayments\Application\Model\Cron::getCurrentPendingCron();
        return $oxid ? TrustPaymentsModule::getControllerUrl('tru_trustPayments_Cron', null, $oxid) : null;
    }
}