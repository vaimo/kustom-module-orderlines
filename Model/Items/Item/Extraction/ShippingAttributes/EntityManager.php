<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes;

use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Dimensions;
use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Tags;
use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Weight;
use Magento\Catalog\Api\Data\ProductInterface;

/**
 * @internal
 */
class EntityManager
{
    /**
     * @var Dimensions
     */
    private Dimensions $dimensions;
    /**
     * @var Tags
     */
    private Tags $tags;
    /**
     * @var Weight
     */
    private Weight $weight;

    /**
     * @param Dimensions $dimensions
     * @param Tags $tags
     * @param Weight $weight
     * @codeCoverageIgnore
     */
    public function __construct(Dimensions $dimensions, Tags $tags, Weight $weight)
    {
        $this->dimensions = $dimensions;
        $this->tags = $tags;
        $this->weight = $weight;
    }

    /**
     * Attaching shipping attributes to the item
     *
     * @param array $item
     * @param ProductInterface $product
     * @return array
     */
    public function attachToItem(array $item, ProductInterface $product): array
    {
        $item['shipping_attributes'] = [
            'dimensions' => $this->dimensions->get($product),
            'tags' => $this->tags->get($product),
            'weight' => $this->weight->get($product)
        ];

        return $item;
    }
}
