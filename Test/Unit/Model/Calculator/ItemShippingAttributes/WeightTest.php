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
use Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Weight;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product;
use Magento\Store\Api\Data\StoreInterface;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Weight
 */
class WeightTest extends TestCase
{
    /**
     * @var Weight
     */
    private Weight $weight;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var StoreInterface
     */
    private StoreInterface $store;

    public function testGetNoUnitValueWasFoundImpliesDefaultOneIsUsed(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn(null);
        $this->product->method('getWeight')
            ->willReturn('100');

        static::assertEquals(100000, $this->weight->get($this->product));
    }

    public function testGetUnknownUnitValueWasFoundImpliesDefaultOneIsUsed(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('abc');
        $this->product->method('getWeight')
            ->willReturn('100');

        static::assertEquals(100000, $this->weight->get($this->product));
    }

    public function testGetLbsUnitValueWasFoundImpliesUsingLbsWeightCalculatorValue(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('lbs');
        $this->product->method('getWeight')
            ->willReturn('100');

        static::assertEquals(45360, $this->weight->get($this->product));
    }

    protected function setUp(): void
    {
        $this->weight = parent::setUpMocks(Weight::class);
        $this->product = $this->mockFactory->create(Product::class);
        $this->store = $this->mockFactory->create(StoreInterface::class);

        $this->product->method('getStore')
            ->willReturn($this->store);
    }
}