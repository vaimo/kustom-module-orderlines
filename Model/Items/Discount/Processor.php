<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Discount;

use Klarna\Base\Model\Quote\Address\Country;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @internal
 */
class Processor
{
    /**
     * @var PrePurchaseCalculator
     */
    private PrePurchaseCalculator $calculator;
    /**
     * @var Country
     */
    private Country $country;

    /**
     * @param PrePurchaseCalculator $calculator
     * @param Country $country
     * @codeCoverageIgnore
     */
    public function __construct(PrePurchaseCalculator $calculator, Country $country)
    {
        $this->calculator = $calculator;
        $this->country = $country;
    }

    /**
     * Processing the data and putting the data into the Parameter instance
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
        if ($this->country->isUsCountry($object)) {
            $this->calculator->calculateSeparateTaxLineData($dataHolder);
        } else {
            $this->calculator->calculateIncludedTaxData($dataHolder);
        }

        $parameter->setDiscountUnitPrice($this->calculator->getUnitPrice());
        $parameter->setDiscountTaxRate($this->calculator->getTaxRate());
        $parameter->setDiscountTotalAmount($this->calculator->getTotalAmount());
        $parameter->setDiscountTaxAmount($this->calculator->getTaxAmount());
        $parameter->setDiscountTitle($this->calculator->getTitle());
        $parameter->setDiscountReference($this->calculator->getReference());
    }
}
