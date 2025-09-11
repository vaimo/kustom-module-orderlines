<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Calculator\ItemShippingAttributes;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Directory\Helper\Data;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @internal
 */
class Weight
{
    private const UNIT_LBS = 2.2046;
    private const UNIT_DEFAULT = 1000;

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
     * Getting back the weight of the product
     *
     * @param ProductInterface $product
     * @return float
     */
    public function get(ProductInterface $product): float
    {
        $weightCalculator = $this->getWeightCalculator($product);
        return round((float)$product->getWeight() * $weightCalculator);
    }

    /**
     * Getting back the weight calculator
     *
     * @param ProductInterface $product
     * @return float
     */
    private function getWeightCalculator(ProductInterface $product): float
    {
        $unit = (string) $this->scopeConfig->getValue(
            Data::XML_PATH_WEIGHT_UNIT,
            ScopeInterface::SCOPE_STORE,
            $product->getStore()
        );

        $weightCalculator = self::UNIT_DEFAULT;
        if ($unit === 'lbs') {
            $weightCalculator /= self::UNIT_LBS;
        }

        return $weightCalculator;
    }
}
