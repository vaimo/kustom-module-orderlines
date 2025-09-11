<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Directory\Helper\Data;

/**
 * @internal
 */
class Weight
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @codeCoverageIgnore
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Getting back the weight for the product
     *
     * @param ProductInterface $product
     * @return float
     */
    public function get(ProductInterface $product): float
    {
        $unit = $this->getWeightUnit($product);
        $weightCalculator = $this->getWeightCalculator($unit);

        return round($product->getWeight() * $weightCalculator);
    }

    /**
     * Getting back the weight unit
     *
     * @param ProductInterface $product
     * @return string
     */
    private function getWeightUnit(ProductInterface $product): string
    {
        return $this->scopeConfig->getValue(
            Data::XML_PATH_WEIGHT_UNIT,
            ScopeInterface::SCOPE_STORE,
            $product->getStore()
        );
    }

    /**
     * Getting back the weight calculator
     *
     * @param string $unit
     * @return float
     */
    private function getWeightCalculator(string $unit): float
    {
        $weightCalculator = 1000;
        if ($unit === 'lbs') {
            $weightCalculator /= 2.2046;
        }

        return $weightCalculator;
    }
}
