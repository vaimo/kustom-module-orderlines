<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction\ShippingAttributes\Entities;

use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Tags;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Collection;
use Magento\Framework\DataObject;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Tags
 */
class TagsTest extends TestCase
{
    /**
     * @var Tags
     */
    private Tags $model;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var Collection
     */
    private Collection $collection;
    /**
     * @var DataObject
     */
    private DataObject $item;

    public function testGetProductCollectionIsNullImpliesReturningEmptyArray(): void
    {
        static::assertEquals([], $this->model->get($this->product));
    }

    public function testGetCollectionHasNoItemsImpliesReturningEmptyArray(): void
    {
        $this->product->method('getCategoryCollection')
            ->willReturn($this->collection);
        $this->collection->method('getItems')
            ->willReturn([]);
        static::assertEquals([], $this->model->get($this->product));
    }

    public function testGetCollectionHasItemsImpliesReturningArrayWithItems(): void
    {
        $expected = 'my_item_name';
        $this->item->method('getName')
            ->willReturn($expected);
        $this->collection->method('getItems')
            ->willReturn([$this->item]);
        $this->product->method('getCategoryCollection')
            ->willReturn($this->collection);

        static::assertEquals([$expected], $this->model->get($this->product));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Tags::class);

        $this->product = $this->mockFactory->create(Product::class);
        $this->collection = $this->mockFactory->create(Collection::class, ['getItems'],['addNameToResult']);
        $this->item = $this->mockFactory->create(DataObject::class, [], ['getName']);
    }
}