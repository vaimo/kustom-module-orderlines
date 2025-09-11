<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction\ShippingAttributes;

use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\EntityManager;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Catalog\Model\Product;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\EntityManager
 */
class EntityManagerTest extends TestCase
{
    /**
     * @var EntityManager
     */
    private EntityManager $model;
    /**
     * @var Product
     */
    private Product $product;

    public function testAttachToItemReturningDimensionsValue(): void
    {
        $expected = ['abc' => 'def'];
        $this->dependencyMocks['dimensions']->method('get')
            ->willReturn($expected);

        $result = $this->model->attachToItem([], $this->product);
        static::assertEquals($expected, $result['shipping_attributes']['dimensions']);
    }

    public function testAttachToItemReturningTagsValue(): void
    {
        $expected = ['abc' => 'def'];
        $this->dependencyMocks['tags']->method('get')
            ->willReturn($expected);

        $result = $this->model->attachToItem([], $this->product);
        static::assertEquals($expected, $result['shipping_attributes']['tags']);
    }

    public function testAttachToItemReturningWeightValue(): void
    {
        $expected = (float) 7;
        $this->dependencyMocks['weight']->method('get')
            ->willReturn($expected);

        $result = $this->model->attachToItem([], $this->product);
        static::assertEquals($expected, $result['shipping_attributes']['weight']);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(EntityManager::class);
        $this->product = $this->mockFactory->create(Product::class);
    }
}