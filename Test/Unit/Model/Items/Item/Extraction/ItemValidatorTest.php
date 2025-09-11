<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction;

use Klarna\Orderlines\Model\Items\Item\Extraction\ItemValidator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Item;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Magento\Bundle\Model\Product\Price;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\ItemValidator
 */
class ItemValidatorTest extends TestCase
{
    /**
     * @var ItemValidator
     */
    private ItemValidator $model;
    /**
     * @var Item
     */
    private Item $magentoQuoteItem;
    /**
     * @var Product
     */
    private Product $product;

    public function testIsBundledProductWithDynamicPriceTypeNotBundledAndNotDynamicImpliesReturningFalse(): void
    {
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn('simple');
        $this->product->method('getPriceType')
            ->willReturn('fixed');

        static::assertFalse($this->model->isBundledProductWithDynamicPriceType($this->magentoQuoteItem));
    }

    public function testIsBundledProductWithDynamicPriceTypeBundledAndNotDynamicImpliesReturningFalse(): void
    {
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn('fixed');

        static::assertFalse($this->model->isBundledProductWithDynamicPriceType($this->magentoQuoteItem));
    }

    public function testIsBundledProductWithDynamicPriceTypeNotBundledButDynamicImpliesReturningFalse(): void
    {
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn('simple');
        $this->product->method('getPriceType')
            ->willReturn(Price::PRICE_TYPE_DYNAMIC);

        static::assertFalse($this->model->isBundledProductWithDynamicPriceType($this->magentoQuoteItem));
    }

    public function testIsBundledProductWithDynamicPriceTypeBundledAndDynamicImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_DYNAMIC);

        static::assertTrue($this->model->isBundledProductWithDynamicPriceType($this->magentoQuoteItem));
    }

    public function testIsBundledProductWithDynamicPriceTypeBundledAndDynamicButGivenAsIntegerImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn(0);

        static::assertTrue($this->model->isBundledProductWithDynamicPriceType($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductNoParentItemImpliesReturningFalse(): void
    {
        static::assertFalse($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductParentNoBundleAndPriceTypeNotFixedImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getParentItem')
            ->willReturn($this->magentoQuoteItem);
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn('simple');
        $this->product->method('getPriceType')
            ->willReturn('dynamic');

        static::assertTrue($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductParentBundleAndPriceTypeNotFixedImpliesReturningFalse(): void
    {
        $this->magentoQuoteItem->method('getParentItem')
            ->willReturn($this->magentoQuoteItem);
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn('dynamic');

        static::assertFalse($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductParentNoBundleButPriceTypeFixedImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getParentItem')
            ->willReturn($this->magentoQuoteItem);
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn('simple');
        $this->product->method('getPriceType')
            ->willReturn(Price::PRICE_TYPE_FIXED);

        static::assertTrue($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductParentBundleAndPriceTypeFixedImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getParentItem')
            ->willReturn($this->magentoQuoteItem);
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn((string) Price::PRICE_TYPE_FIXED);

        static::assertTrue($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }

    public function testHasInvalidParentProductParentBundleAndPriceTypeFixedAsIntValueImpliesReturningTrue(): void
    {
        $this->magentoQuoteItem->method('getParentItem')
            ->willReturn($this->magentoQuoteItem);
        $this->magentoQuoteItem->method('getProductType')
            ->willReturn(Type::TYPE_BUNDLE);
        $this->product->method('getPriceType')
            ->willReturn(1);

        static::assertTrue($this->model->hasInvalidParentProduct($this->magentoQuoteItem));
    }
    
    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(ItemValidator::class);

        $this->product = $this->mockFactory->create(Product::class, [], ['getPriceType']);
        $this->magentoQuoteItem = $this->mockFactory->create(Item::class);
        $this->magentoQuoteItem->method('getProduct')
            ->willReturn($this->product);
    }
}