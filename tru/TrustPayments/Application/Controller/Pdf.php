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


use TrustPayments\Sdk\Model\RenderedDocument;
use TrustPayments\Sdk\Service\TransactionService;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class Webhook.
 */
class Pdf extends \oxview
{
    /**
     * @var \Tru\TrustPayments\Extend\Application\Model\Order
     */
    private $order;
    /**
     * @var TransactionService
     */
    private $service;

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();
        $orderId = TrustPaymentsModule::instance()->getRequestParameter('oxid');
class_exists('oxorder');        $order = oxNew('oxorder');
        /* @var $order \Tru\TrustPayments\Extend\Application\Model\Order */
        if (!$orderId || !$order->load($orderId)) {
            throw new \Exception("No order id supplied, or order could not be loaded: '$orderId'.");
        }
        if (!$order->isTruOrder()) {
            throw new \Exception("Given order is not a Trust Payments order: '$orderId'.");
        }
        $this->order = $order;
        $this->service = new TransactionService(TrustPaymentsModule::instance()->getApiClient());
    }

    /**
     * @throws \Exception
     */
    private function verifyUser()
    {
        if ($this->getUser()->getId() !== $this->order->getOrderUser()->getId() && !$this->isAdmin()) {
            throw new \Exception("Attempting to download document from other user.");
        }
    }

    /**
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    public function packingSlip()
    {
        if (!TrustPaymentsModule::settings()->isDownloadPackingEnabled()) {
            throw new \Exception("Packing slip download is not enabled.");
        }
        $this->verifyUser();

        $document = $this->service->getPackingSlip($this->order->getTrustPaymentsTransaction()->getSpaceId(), $this->order->getTrustPaymentsTransaction()->getTransactionId());

        $this->renderDocument($document);
    }

    /**
     * @throws \Exception
     * @throws \TrustPayments\Sdk\ApiException
     */
    public function invoice()
    {
        if (!TrustPaymentsModule::settings()->isDownloadInvoiceEnabled()) {
            throw new \Exception("Invoice download is not enabled.");
        }
        $this->verifyUser();

        $document = $this->service->getInvoiceDocument($this->order->getTrustPaymentsTransaction()->getSpaceId(), $this->order->getTrustPaymentsTransaction()->getTransactionId());

        $this->renderDocument($document);
    }

    /**
     * Outputs the given document.
     *
     * @param RenderedDocument $document
     */
    private function renderDocument(RenderedDocument $document)
    {
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $document->getTitle() . '.pdf"');
        header('Content-Description: ' . $document->getTitle());
        echo base64_decode($document->getData());
        exit();
    }
}