<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Calculator\ItemShippingAttributes;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection;

/**
 * @internal
 */
class Categories
{
    /**
     * Getting back the categories of the product
     *
     * @param ProductInterface $product
     * @return array
     */
    public function get(ProductInterface $product): array
    {
        $categories = [];

        /** @var Collection $collection */
        $collection = $product->getCategoryCollection();
        if ($collection === null) {
            return $categories;
        }
        $collection->addNameToResult();

        /** @var Category $category */
        foreach ($collection->getItems() as $category) {
            $categories[] = $category->getName();
        }

        return $categories;
    }
}
