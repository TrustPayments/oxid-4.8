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

namespace Tru\TrustPayments\Application\Model;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

;

/**
 * Class Alert.
 */
class Alert
{
    const KEY_MANUAL_TASK = 'manual_task';

    protected static function getTableName()
    {
        return 'truTrustPayments_alert';
    }

    public static function setCount($key, $count) {
        $count = (int)$count;
        $key = \oxdb::getDb()->quote($key);
        $query = "UPDATE `truTrustPayments_alert` SET `trucount`=$count WHERE `trukey`=$key;";
        return \oxdb::getDb()->execute($query) === 1;
    }

    public static function modifyCount($key, $countModifier = 1) {
        $countModifier = (int)$countModifier;
        $key = \oxdb::getDb()->quote($key);
        $query = "UPDATE `truTrustPayments_alert` SET `TRUCOUNT`=`TRUCOUNT`+$countModifier WHERE `trukey`=$key;";
        return \oxdb::getDb()->execute($query) === 1;
    }

    public static function loadAll() {
        $query = "SELECT `TRUKEY`, `TRUCOUNT`, `TRUFUNC`, `TRUTARGET` FROM `truTrustPayments_alert`";
        return \oxdb::getDb()->getAll($query);
    }

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->init(self::getTableName());
    }
}