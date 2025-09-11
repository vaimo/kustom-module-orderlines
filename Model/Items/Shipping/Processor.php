<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Shipping;

use Klarna\Base\Model\Quote\Address\Country;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * @internal
 */
class Processor
{
    /**
     * @var PrePurchaseCalculator
     */
    private PrePurchaseCalculator $prePurchaseCalculator;
    /**
     * @var PostPurchaseCalculator
     */
    private PostPurchaseCalculator $postPurchaseCalculator;
    /**
     * @var Country
     */
    private Country $country;

    /**
     * @param PrePurchaseCalculator $prePurchaseCalculator
     * @param PostPurchaseCalculator $postPurchaseCalculator
     * @param Country $country
     * @codeCoverageIgnore
     */
    public function __construct(
        PrePurchaseCalculator $prePurchaseCalculator,
        PostPurchaseCalculator $postPurchaseCalculator,
        Country $country
    ) {
        $this->prePurchaseCalculator = $prePurchaseCalculator;
        $this->postPurchaseCalculator = $postPurchaseCalculator;
        $this->country = $country;
    }

    /**
     * Processing the data and putting the data into the Parameter instance for the pre purchase
     *
     * @param DataHolder $dataHolder
     * @param Parameter $parameter
     * @param ExtensibleDataInterface $object
     */
    public function processPrePurchase(
        DataHolder $dataHolder,
        Parameter $parameter,
        ExtensibleDataInterface $object
    ): void {
        if (!$this->country->isUsCountry($object)) {
            $this->prePurchaseCalculator->calculateIncludedTaxData($dataHolder);
        } else {
            $this->prePurchaseCalculator->calculateSeparateTaxLineData($dataHolder);
        }

        $parameter->setShippingUnitPrice($this->prePurchaseCalculator->getUnitPrice());
        $parameter->setShippingTaxRate($this->prePurchaseCalculator->getTaxRate());
        $parameter->setShippingTotalAmount($this->prePurchaseCalculator->getTotalAmount());
        $parameter->setShippingTaxAmount($this->prePurchaseCalculator->getTaxAmount());
        $parameter->setShippingDiscountAmount($this->prePurchaseCalculator->getDiscountAmount());
        $parameter->setShippingTitle($this->prePurchaseCalculator->getTitle());
        $parameter->setShippingReference($this->prePurchaseCalculator->getReference());
    }

    /**
     * Processing the data and putting the data into the Parameter instance for the post purchase
     *
     * @param DataHolder $dataHolder
     * @param Parameter $parameter
     * @param OrderInterface $order
     */
    public function processPostPurchase(DataHolder $dataHolder, Parameter $parameter, OrderInterface $order): void
    {
        $this->postPurchaseCalculator->calculate($dataHolder, $order);

        $parameter->setShippingUnitPrice($this->postPurchaseCalculator->getUnitPrice());
        $parameter->setShippingTaxRate($this->postPurchaseCalculator->getTaxRate());
        $parameter->setShippingTotalAmount($this->postPurchaseCalculator->getTotalAmount());
        $parameter->setShippingTaxAmount($this->postPurchaseCalculator->getTaxAmount());
        $parameter->setShippingDiscountAmount($this->postPurchaseCalculator->getDiscountAmount());
        $parameter->setShippingTitle($this->postPurchaseCalculator->getTitle());
        $parameter->setShippingReference($this->postPurchaseCalculator->getReference());
    }
}
