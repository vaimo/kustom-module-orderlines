<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model;

use Klarna\Orderlines\Model\ProductTypeChecker;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product;
use Magento\Bundle\Model\Product\Price;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\ProductTypeChecker
 */
class ProductTypeCheckerTest extends TestCase
{
    /**
     * @var ProductTypeChecker
     */
    private ProductTypeChecker $productTypeChecker;
    /**
     * @var Item
     */
    private Item $item;
    /**
     * @var Product
     */
    private Product $product;

    public function testIsBundledProductWithDynamicPriceTypeIsBundledAndDynamicReturnsTrue(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);
        static::assertTrue($this->productTypeChecker->isBundledProductWithDynamicPriceType($this->item));
    }

    public function testIsBundledProductWithDynamicPriceTypeIsBundledAndFixedReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);
        static::assertFalse($this->productTypeChecker->isBundledProductWithDynamicPriceType($this->item));
    }

    public function testIsBundledProductWithDynamicPriceTypeIsNotBundledAndDynamicReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_SIMPLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);
        static::assertFalse($this->productTypeChecker->isBundledProductWithDynamicPriceType($this->item));
    }

    public function testIsBundledProductWithDynamicPriceTypeIsNotBundledAndFixedReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_SIMPLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);
        static::assertFalse($this->productTypeChecker->isBundledProductWithDynamicPriceType($this->item));
    }

    public function testIsBundledProductWithFixedPriceTypeIsBundledAndFixedReturnsTrue(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);
        static::assertTrue($this->productTypeChecker->isBundledProductWithFixedPriceType($this->item));
    }

    public function isBundledProductWithFixedPriceTypeIsBundledAndDynamicReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);
        static::assertFalse($this->productTypeChecker->isBundledProductWithFixedPriceType($this->item));
    }

    public function isBundledProductWithFixedPriceTypeIsNotBundledAndFixedReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_SIMPLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);
        static::assertFalse($this->productTypeChecker->isBundledProductWithFixedPriceType($this->item));
    }

    public function isBundledProductWithFixedPriceTypeIsNotBundledAndDynamicReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_SIMPLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);
        static::assertFalse($this->productTypeChecker->isBundledProductWithFixedPriceType($this->item));
    }

    public function testIsNonBundleProductProductIsNonBundledReturnsTrue(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_SIMPLE);
        static::assertTrue($this->productTypeChecker->isNonBundleProduct($this->item));
    }

    public function testIsNonBundleProductProductIsBundledReturnsFalse(): void
    {
        $this->item->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        static::assertFalse($this->productTypeChecker->isNonBundleProduct($this->item));
    }

    public function testIsFixedPriceTypePriceTypeIsFixedReturnsTrue(): void
    {
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);
        static::assertTrue($this->productTypeChecker->isFixedPriceType($this->item));
    }

    public function testIsFixedPriceTypePriceTypeIsNotFixedReturnsFalse(): void
    {
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);
        static::assertFalse($this->productTypeChecker->isFixedPriceType($this->item));
    }

    protected function setUp(): void
    {
        $this->productTypeChecker = parent::setUpMocks(ProductTypeChecker::class);

        $this->item = $this->mockFactory->create(Item::class);
        $this->product = $this->mockFactory->create(Product::class, [],['getPriceType']);
        $this->item->method('getProduct')
            ->willReturn($this->product);
    }
}