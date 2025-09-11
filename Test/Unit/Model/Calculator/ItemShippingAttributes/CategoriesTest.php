<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Calculator\ItemShippingAttributes;

use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Categories;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\Category;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Categories
 */
class CategoriesTest extends TestCase
{
    /**
     * @var Categories
     */
    private Categories $categories;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var Collection
     */
    private Collection $collection;
    /**
     * @var Category
     */
    private Category $category;

    public function testGetNoCollectionGivenImpliesReturningEmptyArray(): void
    {
        $this->product->expects($this->once())
            ->method('getCategoryCollection')
            ->willReturn(null);

        $this->assertSame([], $this->categories->get($this->product));
    }

    public function testGetCollectionIsEmptyImpliesReturningEmptyArray(): void
    {
        $this->product->expects($this->once())
            ->method('getCategoryCollection')
            ->willReturn($this->collection);
        $this->collection->method('getItems')
            ->willReturn([]);

        $this->assertSame([], $this->categories->get($this->product));
    }

    public function testGetCollectionHasOneItemImpliesReturningNotEmptyArray(): void
    {
        $this->product->expects($this->once())
            ->method('getCategoryCollection')
            ->willReturn($this->collection);

        $this->category->expects($this->once())
            ->method('getName')
            ->willReturn('Category Name');
        $this->collection->method('getItems')
            ->willReturn([$this->category]);

        $this->assertSame(['Category Name'], $this->categories->get($this->product));
    }

    protected function setUp(): void
    {
        $this->categories = parent::setUpMocks(Categories::class);
        $this->product = $this->mockFactory->create(Product::class);

        $this->collection = $this->createMock(Collection::class);
        $this->category = $this->createMock(Category::class);
    }
}
