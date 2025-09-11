<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction;

use Klarna\Orderlines\Model\Items\Item\Extraction\Container;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\Store;
use Magento\Catalog\Model\Product;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\Container
 */
class ContainerTest extends TestCase
{
    /**
     * @var Container
     */
    private $model;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var Item
     */
    private $parentItem;
    /**
     * @var Store
     */
    private $store;
    /**
     * @var Product
     */
    private $product;

    public function testSetValuesOriginalPriceIsEmptyImpliesReturningOriginalPriceOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getOriginalPrice());
    }

    public function testSetValuesOriginalPriceIsNotEmptyImpliesReturningNotZeroOriginalPrice(): void
    {
        $expected = 123;
        $this->item->method('getBaseOriginalPrice')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getOriginalPrice());
    }

    public function testSetValuesPriceIsEmptyImpliesReturningPriceOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getPrice());
    }

    public function testSetValuesPriceIsNotEmptyImpliesReturningNotZeroPrice(): void
    {
        $expected = 123;
        $this->item->method('getBasePrice')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getPrice());
    }

    public function testSetValuesBasePriceInclTaxIsEmptyImpliesReturningPriceIncludedTaxOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getPriceIncludedTax());
    }

    public function testSetValuesBasePriceInclTaxIsNotEmptyImpliesReturningNotZeroPriceIncludedTax(): void
    {
        $expected = 123;
        $this->item->method('getBasePriceInclTax')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getPriceIncludedTax());
    }

    public function testSetValuesBaseRowTotalIsEmptyImpliesReturningRowTotalOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getRowTotal());
    }

    public function testSetValuesBaseRowTotalIsNotEmptyImpliesReturningNotZeroRowTotal(): void
    {
        $expected = 123;
        $this->item->method('getBaseRowTotal')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getRowTotal());
    }

    public function testSetValuesBaseRowTotalInclTaxIsEmptyImpliesReturningRowTotalIncludedTaxOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getRowTotalIncludedTax());
    }

    public function testSetValuesBaseRowTotalInclTaxIsNotEmptyImpliesReturningNotZeroRowTotalIncludedTax(): void
    {
        $this->item->method('getBaseRowTotal')
            ->willReturn(10);
        $this->item->method('getBaseTaxAmount')
            ->willReturn(11);
        $this->item->method('getDiscountTaxCompensationAmount')
            ->willReturn(12);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(33, $this->model->getRowTotalIncludedTax());
    }

    public function testSetValuesBaseTaxAmountIsEmptyImpliesReturningTaxAmountOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getTaxAmount());
    }

    public function testSetValuesBaseTaxAmountIsNotEmptyImpliesReturningNotZeroTaxAmount(): void
    {
        $expected = 123;
        $this->item->method('getBaseTaxAmount')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getTaxAmount());
    }

    public function testSetValuesBaseDiscountAmountIsEmptyImpliesReturningDiscountAmountOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getDiscountAmount());
    }

    public function testSetValuesBaseDiscountAmountIsNotEmptyImpliesReturningNotZeroDiscountAmount(): void
    {
        $expected = 123;
        $this->item->method('getBaseDiscountAmount')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getDiscountAmount());
    }

    public function testSetValuesSettingTheNameAndReturningIt(): void
    {
        $expected = 'item_name';
        $this->item->method('getName')
            ->willReturn($expected);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getName());
    }

    public function testSetValuesSettingTheQtyAndInvoiceRefundItemQtyIsGreaterZeroReturningInvoiceRefundItemQty(): void
    {
        $expected = 3;
        $this->item->method('getCurrentInvoiceRefundItemQty')
            ->willReturn($expected);
        $this->item->method('getQty')
            ->willReturn(2);
        $this->item->method('getQtyOrdered')
            ->willReturn(1);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getQty());
    }

    public function testSetValuesSettingTheQtyAndInvoiceRefundItemQtyIsZeroAndQtyNotZeroReturningQty(): void
    {
        $expected = 2;
        $this->item->method('getQty')
            ->willReturn($expected);
        $this->item->method('getQtyOrdered')
            ->willReturn(1);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getQty());
    }

    public function testSetValuesSettingTheQtyAndInvoiceRefundItemQtyIsZeroAndQtyIsZeroReturningQtyOrdered(): void
    {
        $expected = 1;
        $this->item->method('getQtyOrdered')
            ->willReturn($expected);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getQty());
    }

    public function testSetValuesSettingTheSkuAndReturningIt(): void
    {
        $expected = 'sku_id';
        $this->item->method('getSku')
            ->willReturn($expected);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getSku());
    }

    public function testSetValuesTaxPercentIsEmptyImpliesReturningTaxPercentOfZero(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(0, $this->model->getTaxPercent());
    }

    public function testSetValuesTaxPercentIsNotEmptyImpliesReturningNotZeroTaxPercent(): void
    {
        $expected = 123;
        $this->item->method('getTaxPercent')
            ->willReturn($expected);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getTaxPercent());
    }

    public function testSetValuesSettingTheProductAndReturningIt(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertSame($this->product, $this->model->getProduct());
    }

    public function testSetValuesSettingTheProductTypeAndReturningIt(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals('abc', $this->model->getProductType());
    }

    public function testSetValuesSettingTheProductUrlAndReturningIt(): void
    {
        $expected = 'product_url';
        $this->product->method('getUrlInStore')
            ->willReturn($expected);

        $this->model->setValues($this->item, $this->store);
        static::assertEquals($expected, $this->model->getProductUrl());
    }

    public function testSetValuesSettingTheImageUrlButProductHasNoImageImpliesImageUrlIsEmpty(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertEquals('', $this->model->getImageUrl());
    }

    public function testSetValuesSettingTheImageUrlAndProductHasImageImpliesImageUrlIsNotEmpty(): void
    {
        $this->store->method('getBaseUrl')
            ->willReturn('my_base_url/');
        $this->product->method('getSmallImage')
            ->willReturn('/my_image.jpg');

        $this->model->setValues($this->item, $this->store);
        static::assertEquals('my_base_url/catalog/product/my_image.jpg', $this->model->getImageUrl());
    }

    public function testSetValuesSettingStoreInstanceAndReturningIt(): void
    {
        $this->model->setValues($this->item, $this->store);
        static::assertSame($this->store, $this->model->getStore());
    }

    public function testSetValuesItemsIsProductOfBundledProductImpliesUsingQtyOfParentBundledProduct()
    {
        $this->item->method('getQty')
            ->willReturn(1);
        $this->item->method('getParentItem')
            ->willReturn($this->parentItem);
        $this->parentItem->method('getQty')
            ->willReturn(3);
        $this->model->setValues($this->item, $this->store);
        static::assertEquals(3, $this->model->getQty());
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Container::class);

        $this->product = $this->createSingleMock(
            Product::class,
            [
                'getUrlInStore',
                'getStore'
            ],
            ['getSmallImage']
        );
        $this->item = $this->createSingleMock(
            Item::class,
            [
                'getProduct',
                'getProductType',
                'getSku',
                'getQty',
                'getParentItem',
                'getName',
                'getBaseOriginalPrice'
            ],
            [
                'getTaxPercent',
                'getQtyOrdered',
                'getCurrentInvoiceRefundItemQty',
                'getBaseDiscountAmount',
                'getBaseTaxAmount',
                'getBaseRowTotal',
                'getDiscountTaxCompensationAmount',
                'getBasePriceInclTax',
                'getBasePrice',
            ]
        );
        $this->item->method('getProduct')
            ->willReturn($this->product);
        $this->item->method('getProductType')
            ->willReturn('abc');
        $this->dependencyMocks['itemResolver']->method('getFinalProduct')
            ->willReturn($this->product);

        $this->store = $this->createSingleMock(Store::class);
        $this->product->method('getStore')
            ->willReturn($this->store);

        $this->parentItem = $this->createSingleMock(Item::class);
    }
}