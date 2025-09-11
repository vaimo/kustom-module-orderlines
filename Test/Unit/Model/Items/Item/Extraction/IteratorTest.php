<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Item\Extraction\Container;
use Klarna\Orderlines\Model\Items\Item\Extraction\Iterator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\Iterator
 */
class IteratorTest extends TestCase
{
    /**
     * @var Iterator
     */
    private Iterator $model;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Item
     */
    private Item $magentoQuoteItem;
    /**
     * @var Container
     */
    private Container $container;

    public function testGetCalculatedItemsNoItemsImpliesNoOrderLineItemCalculated(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn([]);

        $this->container->expects(static::never())
            ->method('setValues');
        $this->dependencyMocks['coordinator']->expects(static::never())
            ->method('createOrderLineItem');
        static::assertEquals([], $this->model->getCalculatedItems($this->dataHolder, $this->magentoQuote));
    }

    public function testGetCalculatedItemsIsBundledProductImpliesNoOrderLineItemCalculated(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn([$this->magentoQuoteItem]);
        $this->dependencyMocks['itemValidator']->method('isBundledProductWithDynamicPriceType')
            ->with($this->magentoQuoteItem)
            ->willReturn(true);

        $this->container->expects(static::never())
            ->method('setValues');
        $this->dependencyMocks['coordinator']->expects(static::never())
            ->method('createOrderLineItem');
        static::assertEquals([], $this->model->getCalculatedItems($this->dataHolder, $this->magentoQuote));
    }

    public function testGetCalculatedItemsHasInvalidParentProductImpliesNoOrderLineItemCalculated(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn([$this->magentoQuoteItem]);
        $this->dependencyMocks['itemValidator']->method('hasInvalidParentProduct')
            ->with($this->magentoQuoteItem)
            ->willReturn(true);

        $this->container->expects(static::never())
            ->method('setValues');
        $this->dependencyMocks['coordinator']->expects(static::never())
            ->method('createOrderLineItem');
        static::assertEquals([], $this->model->getCalculatedItems($this->dataHolder, $this->magentoQuote));
    }

    public function testGetCalculatedItemsIsBundledProductAndInvalidParentProductImpliesNoOrderLineItemCalculated(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn([$this->magentoQuoteItem]);
        $this->dependencyMocks['itemValidator']->method('isBundledProductWithDynamicPriceType')
            ->with($this->magentoQuoteItem)
            ->willReturn(true);
        $this->dependencyMocks['itemValidator']->method('hasInvalidParentProduct')
            ->with($this->magentoQuoteItem)
            ->willReturn(true);

        $this->container->expects(static::never())
            ->method('setValues');
        $this->dependencyMocks['coordinator']->expects(static::never())
            ->method('createOrderLineItem');
        static::assertEquals([], $this->model->getCalculatedItems($this->dataHolder, $this->magentoQuote));
    }

    public function testGetCalculatedItemsCalculatingTheOrderLineItem(): void
    {
        $expected = ['abc' => 'def'];
        $this->dataHolder->method('getItems')
            ->willReturn([$this->magentoQuoteItem]);

        $this->container->expects(static::once())
            ->method('setValues');
        $this->dependencyMocks['coordinator']->expects(static::once())
            ->method('createOrderLineItem')
            ->willReturn($expected);
        static::assertEquals([$expected], $this->model->getCalculatedItems($this->dataHolder, $this->magentoQuote));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Iterator::class);

        $this->container = $this->mockFactory->create(Container::class);
        $this->dependencyMocks['containerFactory']->method('create')
            ->willReturn($this->container);

        $store = $this->mockFactory->create(Store::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->dataHolder->method('getStore')
            ->willReturn($store);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoQuoteItem = $this->mockFactory->create(Item::class);
    }
}