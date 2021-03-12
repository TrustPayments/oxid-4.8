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


namespace Tru\TrustPayments\Core\Adapter;
require_once(OX_BASE_PATH . 'modules/tru/TrustPayments/autoload.php');

use Monolog\Logger;
use \oxobjectexception as oxObjectException;
use TrustPayments\Sdk\Model\LineItemCreate;
use TrustPayments\Sdk\Model\LineItemType;
use TrustPayments\Sdk\Model\TaxCreate;
use Tru\TrustPayments\Core\TrustPaymentsModule;

/**
 * Class BasketAdapter
 * Converts Oxid Basket Data into data which can be fed into the TrustPayments SDK.
 *
 * @codeCoverageIgnore
 */
class BasketAdapter implements ILineItemAdapter
{
    private $basket = null;

    public function __construct(\oxbasket $basket)
    {
        $this->basket = $basket;
    }

    private function getVouchers()
    {
        $vouchers = array();
        foreach ($this->basket->getVouchers() as $id => $simpleVoucher) {
class_exists('oxvoucher');        	$voucher = oxNew('oxvoucher');
            /* @var $voucher Voucher */
            if ($voucher->load($simpleVoucher->sVoucherId)) {
                try {
                    $serie = $voucher->getSerie();
                    /* @var $serie VoucherSerie */
                    $vouchers[$id] =
                        array(
                            'voucher' => $voucher,
                            'singleuse' => !empty($serie->oxvoucherseries__oxcalculateonce->value),
                            'used' => false,
                            'serie' => $serie
                        );
                } catch (oxObjectException $e) {
                    TrustPaymentsModule::log(Logger::ERROR, "Unable to load voucher serie for voucher with id {$simpleVoucher->sVoucherId}: {$e->getMessage()}");
                }
            } else {
                TrustPaymentsModule::log(Logger::ERROR, "Unable to load voucher with id {$simpleVoucher->sVoucherId}.");
            }
        }
        return $vouchers;
    }

    private function createVoucherDiscounts(\oxbasketItem $basketItem)
    {
        $discounts = array();
        $netto = $basketItem->getPrice()->getNettoPrice();
        foreach ($this->getVouchers() as $voucher) {
            if ($voucher['singleuse'] && $voucher['used']) {
                break;
            }
            if ($voucher['serie']->oxvoucherseries__oxdiscounttype->value === 'percent') {
                $price = $netto * ($voucher['serie']->oxvoucherseries__oxdiscount->value) / 100;
            } else {
                $itemProportion = $basketItem->getPrice()->getNettoPrice() / $this->basket->getProductsPrice()->getNettoSum();
                $price = $voucher['serie']->oxvoucherseries__oxdiscount->value * $itemProportion;
            }

            if ($price) {
                $netto -= $price;

                $discounts[] = array(
                    'id' => $voucher['serie']->oxvoucherseries__oxid->value . '_' . $basketItem->getProductId(),
                    'code' => $voucher['voucher']->oxvouchers__oxvouchernr->value,
                    'price' => $price,
                    'vat' => $basketItem->getPrice()->getVat()
                );
            }

            $voucher['used'] = true;
        }
        return $discounts;
    }

    public function getLineItemData()
    {
        $items = array();

        $voucherDiscounts = array();

        foreach ($this->basket->getContents() as $basketItem) {
            /* @var $basketItem BasketItem */
            $items[] = $this->extractLineItemFromBasketItem($basketItem);

            if (!$basketItem->isSkipDiscount()) {
                $voucherDiscounts += $this->createVoucherDiscounts($basketItem);
            }
        }

        foreach ($voucherDiscounts as $discount) {
            $items[] = $this->createLineItemFromVoucherDiscount($discount);
        }

        $optional = array(
            $this->getLineItemFromPrice($this->basket->getDeliveryCost(), 'shipping_fee', TrustPaymentsModule::instance()->translate('Delivery Fee'), LineItemType::FEE),
        	$this->getLineItemFromPrice($this->basket->getPaymentCost(), 'payment_fee', TrustPaymentsModule::instance()->translate('Payment Fee')),
        	$this->getLineItemFromPrice($this->basket->getGiftCardCost(), 'gift_card', TrustPaymentsModule::instance()->translate('Gift Card')),
        	$this->getLineItemFromPrice($this->basket->getWrappingCost(), 'wrapping_fee', TrustPaymentsModule::instance()->translate('Wrapping Fee')),
        	$this->getLineItemFromPrice($this->basket->getTotalDiscount(), 'total_discount', TrustPaymentsModule::instance()->translate('Total Discount'), LineItemType::DISCOUNT),
        );

        foreach ($optional as $item) {
            if ($item) {
                $items[] = $item;
            }
        }

        return $items;
    }

    private function createLineItemFromVoucherDiscount(array $discount)
    {
        $lineItem = new LineItemCreate();
        /** @noinspection PhpParamsInspection */
        $lineItem->setType(LineItemType::FEE);
        $price = $discount['price'] * -1 * (1 + ($discount['vat'] / 100));
        $lineItem->setAmountIncludingTax(\oxregistry::getUtils()->fRound($price, $this->basket->getBasketCurrency()));
        $lineItem->setName('Voucher ' . $discount['code']);
        $lineItem->setQuantity(1);
        $lineItem->setUniqueId($discount['id']);
        $lineItem->setSku($discount['code']);
        $tax = new TaxCreate();
        $tax->setTitle(TrustPaymentsModule::instance()->translate('VAT'));
        $tax->setRate($discount['vat']);
        $lineItem->setTaxes(array(
            $tax
        ));
        return $lineItem;
    }

    /**
     * @param BasketItem $basketItem
     * @return LineItemCreate
     */
    private function extractLineItemFromBasketItem(\oxbasketItem $basketItem)
    {
        $lineItem = new LineItemCreate();
        $lineItem->setName($basketItem->getTitle());
        $lineItem->setUniqueId($this->basket->getItemKey($basketItem->getProductId()));
        $lineItem->setSku($basketItem->getProductId());
        $lineItem->setQuantity($basketItem->getAmount());

        $price = $basketItem->getPrice();
        /* @var $price Price */
        $lineItem->setAmountIncludingTax($price->getBruttoPrice());

        $tax = new TaxCreate();
        $tax->setRate($price->getVat());
        $tax->setTitle(TrustPaymentsModule::instance()->translate('VAT'));
        $lineItem->setTaxes(array(
            $tax
        ));
        /** @noinspection PhpParamsInspection */
        $lineItem->setType(LineItemType::PRODUCT);
        
        return $lineItem;
    }

    private function getLineItemFromPrice(\oxprice $price = null, $id, $name, $type = LineItemType::FEE)
    {
        if ($price && $price->getBruttoPrice() > 0) {
            $lineItem = new LineItemCreate();
            $lineItem->setName($name);
            $lineItem->setSku($id);
            $lineItem->setUniqueId($id);
            $lineItem->setAmountIncludingTax($price->getBruttoPrice());

            $tax = new TaxCreate();
            $tax->setRate($price->getVat());
            $tax->setTitle(TrustPaymentsModule::instance()->translate('VAT'));
            $lineItem->setTaxes(array(
                $tax
            ));

            $lineItem->setQuantity(1);
            /** @noinspection PhpParamsInspection */
            $lineItem->setType($type);

            return $lineItem;
        }
        return null;
    }

}