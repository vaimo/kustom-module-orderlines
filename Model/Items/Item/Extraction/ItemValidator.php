<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction;

use Magento\Bundle\Model\Product\Price;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Catalog\Model\Product\Type;

/**
 * @internal
 */
class ItemValidator
{

    /**
     * Returns true if the item is a bundled product with a dynamic price type
     *
     * @param ExtensibleDataInterface $item
     * @return bool
     */
    public function isBundledProductWithDynamicPriceType(ExtensibleDataInterface $item): bool
    {
        return Type::TYPE_BUNDLE === $item->getProductType()
            && (string) Price::PRICE_TYPE_DYNAMIC === (string) $item->getProduct()->getPriceType();
    }

    /**
     * Returns true if the item has an invalid parent product
     *
     * @param ExtensibleDataInterface $item
     * @return bool
     */
    public function hasInvalidParentProduct(ExtensibleDataInterface $item): bool
    {
        $parentItem = $item->getParentItem();
        if ($parentItem === null) {
            return false;
        }

        $parentProductType = $parentItem->getProductType();

        return Type::TYPE_BUNDLE !== $parentProductType ||
            (string) Price::PRICE_TYPE_FIXED === (string) $parentItem->getProduct()->getPriceType();
    }
}
