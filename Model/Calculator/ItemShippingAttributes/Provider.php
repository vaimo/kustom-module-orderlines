<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Calculator\ItemShippingAttributes;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @internal
 */
class Provider
{
    /**
     * @var Weight
     */
    private Weight $weight;
    /**
     * @var Dimensions
     */
    private Dimensions $dimensions;
    /**
     * @var Categories
     */
    private Categories $categories;

    /**
     * @param Weight $weight
     * @param Dimensions $dimensions
     * @param Categories $categories
     * @codeCoverageIgnore
     */
    public function __construct(Weight $weight, Dimensions $dimensions, Categories $categories)
    {
        $this->weight = $weight;
        $this->dimensions = $dimensions;
        $this->categories = $categories;
    }

    /**
     * Adding shipping attributes to the given item array
     *
     * @param array $itemResult
     * @param ProductInterface $product
     * @return array
     */
    public function addShippingAttributes(array $itemResult, ProductInterface $product): array
    {
        $itemResult['shipping_attributes'] = [
            'weight'     => $this->weight->get($product),
            'dimensions' => $this->dimensions->get($product),
            'tags'       => $this->categories->get($product)
        ];

        return $itemResult;
    }
}
