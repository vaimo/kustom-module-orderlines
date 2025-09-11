<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction\ShippingAttributes\Entities;

use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Weight;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Catalog\Model\Product;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\Entities\Weight
 */
class WeightTest extends TestCase
{
    /**
     * @var Weight
     */
    private Weight $model;
    /**
     * @var Product
     */
    private Product $product;

    public function testGetUnitIsLbsAndProductWeightIsZeroReturningValueZero(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('lbs');

        static::assertEquals(0, $this->model->get($this->product));
    }

    public function testGetUnitIsLbsAndProductWeightIsNotZeroReturningValueOfNotZero(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('lbs');
        $this->product->method('getWeight')
            ->willReturn(12);

        static::assertEquals(5443, $this->model->get($this->product));
    }

    public function testGetUnitIsUnknownAndProductWeightIsZeroReturningValueZero(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('__');

        static::assertEquals(0, $this->model->get($this->product));
    }

    public function testGetUnitIsUnknownAndProductWeightIsNotZeroReturningValueOfNotZero(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('__');
        $this->product->method('getWeight')
            ->willReturn(12);

        static::assertEquals(12000, $this->model->get($this->product));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Weight::class);
        $this->product = $this->mockFactory->create(Product::class);
    }
}