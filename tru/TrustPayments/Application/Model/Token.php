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

/**
 * This entity holds data about a token on the gateway.
 */
class Token extends \oxbase
{

	private $_sTableName = 'truTrustPayments_token';
	protected $_aSkipSaveFields = ['oxtimestamp', 'truupdated'];

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->init($this->_sTableName);
    }

    public function getTokenId()
    {
        return $this->getFieldData('trutokenid');
    }

    public function getState()
    {
        return $this->getFieldData('trustate');
    }

    public function getSpaceId()
    {
        return $this->getFieldData('truspaceid');
    }

    public function getName()
    {
        return $this->getFieldData('truname');
    }

    public function getCustomerId()
    {
        return $this->getFieldData('trucustomerid');
    }

    public function getPaymentMethodId()
    {
        return $this->getFieldData('trupaymentmethodid');
    }

    public function getConnectorId()
    {
        return $this->getFieldData('truconnectorid');
    }

    public function setTokenId($value)
    {
        $this->_setFieldData('trutokenid', $value);
    }

    public function setState($value)
    {
        $this->_setFieldData('trustate', $value);
    }

    public function setSpaceId($value)
    {
        $this->_setFieldData('truspaceid', $value);
    }

    public function setName($value)
    {
        $this->_setFieldData('truname', $value);
    }

    public function setCustomerId($value)
    {
        $this->_setFieldData('trucustomerid', $value);
    }

    public function setPaymentMethodId($value)
    {
        $this->_setFieldData('trupaymentmethodid', $value);
    }

    public function setConnectorId($value)
    {
        $this->_setFieldData('truconnectorid', $value);
    }

    public function loadByToken($spaceId, $tokenId)
    {
        $query = $this->buildSelectString(array('truspaceid' => $spaceId, 'trutokenid' => $tokenId));
        return $this->assignRecord($query);
    }
}