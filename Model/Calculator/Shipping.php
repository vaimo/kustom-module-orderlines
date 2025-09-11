<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Calculator;

use Klarna\Orderlines\Model\Container\DataHolder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Tax\Model\Calculation;
use Magento\Customer\Model\Address\AddressModelInterface;

/**
 * @api
 */
class Shipping
{

    /**
     * Getting back the tax rate
     *
     * @param DataHolder $dataHolder
     * @return float
     */
    public function getTaxRate(DataHolder $dataHolder): float
    {
        return $this->getTaxRateByAddress($dataHolder->getShippingAddress());
    }

    /**
     * Calculating and getting back the tax rate by shipping costs
     *
     * @param AddressModelInterface $address
     * @param float $shippingAmount
     * @return float
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function getTaxRateByShippingCosts(AddressModelInterface $address, float $shippingAmount): float
    {
        return $this->getTaxRateByAddress($address);
    }

    /**
     * Getting back the tax rate by address
     *
     * @param AddressModelInterface $address
     * @return float
     */
    private function getTaxRateByAddress(AddressModelInterface $address): float
    {
        $baseShippingAmount = (float) $address->getBaseShippingAmount();
        if (empty($baseShippingAmount)) {
            return (float) 0;
        }

        $singleTaxRate = $this->getUsedSingleTaxRate($address);
        if ($singleTaxRate > 0) {
            return $singleTaxRate;
        }

        return $address->getBaseShippingTaxAmount() /($baseShippingAmount) * 100;
    }

    /**
     * Getting back the tax rate if just one was applied else 0
     *
     * @param AddressModelInterface $address
     * @return float
     */
    private function getUsedSingleTaxRate(AddressModelInterface $address): float
    {
        $addressData = $address->getData();
        if (isset($addressData['items_applied_taxes']) && isset($addressData['items_applied_taxes']['shipping'])) {
            $shipping = $addressData['items_applied_taxes']['shipping'];

            if (count($shipping) === 1) {
                return (float) $shipping[0]['percent'];
            }
        }

        return (float) 0;
    }
}
