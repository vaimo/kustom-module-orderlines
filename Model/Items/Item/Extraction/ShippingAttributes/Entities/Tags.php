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

/**
 * @internal
 */
class Tags
{

    /**
     * Getting back the tags for the product
     *
     * @param ProductInterface $product
     * @return array
     */
    public function get(ProductInterface $product): array
    {
        $categories = [];

        $collection = $product->getCategoryCollection();
        if ($collection === null) {
            return $categories;
        }

        $collection->addNameToResult();
        foreach ($collection->getItems() as $category) {
            $categories[] = $category->getName();
        }

        return $categories;
    }
}
