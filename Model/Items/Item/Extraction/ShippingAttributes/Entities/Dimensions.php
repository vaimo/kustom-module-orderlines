<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities;

use Klarna\AdminSettings\Model\Configurations\ShippingOptions;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @internal
 */
class Dimensions
{
    public const UNIT_INCH = 25.4;
    public const UNIT_CM = 10;

    /**
     * @var ShippingOptions
     */
    private ShippingOptions $shippingOptions;

    /**
     * @param ShippingOptions $shippingOptions
     * @codeCoverageIgnore
     */
    public function __construct(ShippingOptions $shippingOptions)
    {
        $this->shippingOptions = $shippingOptions;
    }

    /**
     * Getting back the dimension values for the product
     *
     * @param ProductInterface $product
     * @return array
     */
    public function get(ProductInterface $product): array
    {
        $store = $product->getStore();
        $unit = $this->shippingOptions->getProductSizeUnit($store);
        $dimensionCalculator = $this->getDimensionCalculator($unit);

        return [
            'height' => $this->calculate(
                $product,
                $dimensionCalculator,
                $this->shippingOptions->getProductHeightAttribute($store)
            ),
            'width'  => $this->calculate(
                $product,
                $dimensionCalculator,
                $this->shippingOptions->getProductWidthAttribute($store)
            ),
            'length' => $this->calculate(
                $product,
                $dimensionCalculator,
                $this->shippingOptions->getProductLengthAttribute($store)
            )
        ];
    }

    /**
     * Calculating the product dimension
     *
     * @param ProductInterface $product
     * @param float $dimensionCalculator
     * @param string $attributeCode
     * @return float
     */
    private function calculate(ProductInterface $product, float $dimensionCalculator, string $attributeCode): float
    {
        if (!is_numeric($product->getData($attributeCode))) {
            return 0;
        }

        return round($product->getData($attributeCode) * $dimensionCalculator);
    }

    /**
     * Getting back the dimension calculator
     *
     * @param string $unit
     * @return float
     */
    private function getDimensionCalculator(string $unit): float
    {
        switch ($unit) {
            case 'cm':
                return self::UNIT_CM;
            case 'inch':
                return self::UNIT_INCH;
            case 'mm':
            default:
                return 1;
        }
    }
}
