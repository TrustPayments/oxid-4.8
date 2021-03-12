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
use TrustPayments\Sdk\Model\Refund;
use TrustPayments\Sdk\Model\TransactionCompletion;
use TrustPayments\Sdk\Model\TransactionVoid;
use Tru\TrustPayments\Core\Service\JobService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class AbstractJob.
 */
abstract class AbstractJob extends \oxbase
{
	protected $_aSkipSaveFields = ['oxtimestamp', 'truupdated'];
    private $sdkObject;

    /**
     * @return mixed
     */
    public function getSdkObject()
    {
        return $this->sdkObject;
    }

    /**
     * @param mixed $sdkObject
     */
    public function setSdkObject($sdkObject)
    {
        $this->sdkObject = $sdkObject;
    }


    public function setJobId($value)
    {
        $this->_setFieldData('trujobid', $value);
    }

    public function getJobId()
    {
        return $this->getFieldData('trujobid');
    }

    public function setTransactionId($value)
    {
        $this->_setFieldData('trutransactionid', $value);
    }

    public function getTransactionId()
    {
        return $this->getFieldData('trutransactionid');
    }

    public function setState($value)
    {
        $this->_setFieldData('trustate', $value);
    }

    public function getState()
    {
        return $this->getFieldData('trustate');
    }

    public function setSpaceId($value)
    {
        $this->_setFieldData('truspaceid', $value);
    }

    public function getSpaceId()
    {
        return $this->getFieldData('truspaceid');
    }

    public function setOrderId($value)
    {
        $this->_setFieldData('oxorderid', $value);
    }

    public function getOrderId()
    {
        return $this->getFieldData('oxorderid');
    }

    public function setFailureReason($value)
    {
        $this->_setFieldData('trufailurereason', base64_encode(serialize($value)));
    }

    public function getFailureReason()
    {
        $value = unserialize(base64_decode($this->getFieldData('trufailurereason')));
        if (is_array($value)) {
            $value = TrustPaymentsModule::instance()->TrustPaymentsTranslate($value);
        }
        return $value;
    }

    public function loadByOrder($orderId, $targetStates = array())
    {
        $this->_addField('oxid', 0);
        $query = $this->buildSelectString(['oxorderid' => $orderId]);
        if (!empty($targetStates)) {
            $query .= " AND `trustate` in ('" . implode("', '", $targetStates) . "')";
        }
        $this->_isLoaded = $this->assignRecord($query);
        return $this->_isLoaded;
    }

    public function loadByJob($jobId, $spaceId)
    {
        $this->_addField('oxid', 0);
        $query = $this->buildSelectString(['trujobid' => $jobId, 'truspaceid' => $spaceId]);
        $this->_isLoaded = $this->assignRecord($query);
        return $this->_isLoaded;
    }

    /**
     * @throws \Exception
     */
    public function pull()
    {
        $this->apply($this->getService()->read($this));
    }

    /**
     * @return JobService
     */
    protected abstract function getService();

    /**
     * @param TransactionVoid|TransactionCompletion|Refund $job
     * @throws \Exception
     */
    public function apply($job)
    {
        $this->setJobId($job->getId());
        $this->setSpaceId($job->getLinkedSpaceId());

        // getState not in TransactionAwareEntity
        if ($job instanceof TransactionCompletion || $job instanceof TransactionVoid || $job instanceof Refund) {
            $this->setState($job->getState());
        }
        if ($job instanceof Refund) {
            $this->setTransactionId($job->getTransaction()->getId());
        } else {
            $this->setTransactionId($job->getLinkedTransaction());
        }
        $this->setSdkObject($job);
        $this->_isLoaded = true;
        $this->save();
    }

    protected function createNotSentQuery() {
        $table = $this->getCoreTableName();
        $createState = JobService::getCreationState();
        return "SELECT `TRUJOBID`, `TRUSPACEID` FROM `$table` WHERE `TRUSTATE` = '$createState';";
    }

    public function loadNotSentIds()
    {
        return \oxdb::getDb()->select($this->createNotSentQuery());
    }
}