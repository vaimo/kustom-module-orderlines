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
use Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Dimensions;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Catalog\Model\Product;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Dimensions
 */
class DimensionsTest extends TestCase
{
    /**
     * @var Dimensions
     */
    private Dimensions $dimensions;
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var StoreInterface
     */
    private StoreInterface $store;

    public function testGetUnknownUnitGivenAndUnknownAttributeGivenImpliesUsingDefaultCalculatorValueAndReturningZeroHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['height']);
    }

    public function testGetUnknownUnitGivenAndUnknownAttributeGivenImpliesUsingDefaultCalculatorValueAndReturningZeroWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['width']);
    }

    public function testGetUnknownUnitGivenAndUnknownAttributeGivenImpliesUsingDefaultCalculatorValueAndReturningZeroLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['length']);
    }

    public function testGetKnownUnitGivenAndUnknownAttributeGivenImpliesUsingTargetCalculatorValueAndReturningZeroHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['height']);
    }

    public function testGetKnownUnitGivenAndUnknownAttributeGivenImpliesUsingTargetCalculatorValueAndReturningZeroWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['width']);
    }

    public function testGetKnownUnitGivenAndUnknownAttributeGivenImpliesUsingTargetCalculatorValueAndReturningZeroLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => null,
                    'ccc' => null,
                    'ddd' => null
                }
            );

        static::assertEquals(0, $this->dimensions->get($this->product)['length']);
    }

    public function testGetCmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(50, $this->dimensions->get($this->product)['height']);
    }

    public function testGetCmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(70, $this->dimensions->get($this->product)['width']);
    }

    public function testGetCmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('cm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(90, $this->dimensions->get($this->product)['length']);
    }

    public function testGetInchUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('inch');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(127, $this->dimensions->get($this->product)['height']);
    }

    public function testGetInchUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('inch');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(178, $this->dimensions->get($this->product)['width']);
    }

    public function testGetInchUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('inch');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(229, $this->dimensions->get($this->product)['length']);
    }

    public function testGetMmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('mm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(5, $this->dimensions->get($this->product)['height']);
    }

    public function testGetMmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('mm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(7, $this->dimensions->get($this->product)['width']);
    }

    public function testGetMmUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('mm');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(9, $this->dimensions->get($this->product)['length']);
    }

    public function testGetUnknownUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingHeightValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(5, $this->dimensions->get($this->product)['height']);
    }

    public function testGetUnknownUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingWidthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(7, $this->dimensions->get($this->product)['width']);
    }

    public function testGetUnknownUnitGivenKnownAttributeGivenImpliesUsingTargetCalculatorForCalculatingLengthValue(): void
    {
        $this->dependencyMocks['shippingOptions']->method('getProductSizeUnit')
            ->willReturn('aaa');
        $this->dependencyMocks['shippingOptions']->method('getProductHeightAttribute')
            ->willReturn('bbb');
        $this->dependencyMocks['shippingOptions']->method('getProductWidthAttribute')
            ->willReturn('ccc');
        $this->dependencyMocks['shippingOptions']->method('getProductLengthAttribute')
            ->willReturn('ddd');
        $this->product->method('getData')
            ->willReturnCallback(fn($data) =>
                match($data) {
                    'bbb' => 5,
                    'ccc' => 7,
                    'ddd' => 9,
                }
            );

        static::assertEquals(9, $this->dimensions->get($this->product)['length']);
    }

    protected function setUp(): void
    {
        $this->dimensions = parent::setUpMocks(Dimensions::class);
        $this->product = $this->mockFactory->create(Product::class);
        $this->store = $this->mockFactory->create(StoreInterface::class);

        $this->product->method('getStore')
            ->willReturn($this->store);
    }
}
