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
use Klarna\Orderlines\Model\Items\Item\Extraction\Coordinator;
use Magento\Quote\Model\Quote;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Catalog\Model\Product;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\Coordinator
 */
class CoordinatorTest extends TestCase
{
    /**
     * @var Coordinator
     */
    private Coordinator $model;
    /**
     * @var Container
     */
    private Container $container;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;
    /**
     * @var Product
     */
    private Product $product;

    public function testCreateOrderLineItemIsUsCountryCalculatingExclusiveTaxes(): void
    {
        $expected = ['abc' => 'def'];
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(true);
        $this->dependencyMocks['exclusiveTaxCalculator']->method('getOrderLineItem')
            ->willReturn($expected);

        static::assertEquals($expected, $this->model->createOrderLineItem($this->container, $this->magentoQuote, $this->dataHolder));
    }

    public function testCreateOrderLineItemIsNotUsCountryCalculatingInclusiveTaxes(): void
    {
        $expected = ['abc' => 'def'];
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(false);
        $this->dependencyMocks['inclusiveTaxCalculator']->method('getOrderLineItem')
            ->willReturn($expected);

        static::assertEquals($expected, $this->model->createOrderLineItem($this->container, $this->magentoQuote, $this->dataHolder));
    }

    public function testCreateOrderLineItemAttachingEntitiesToTheItem(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(false);
        $this->dependencyMocks['inclusiveTaxCalculator']->method('getOrderLineItem')
            ->willReturn([]);
        $this->dependencyMocks['productCollection']->method('get')
            ->willReturn([$this->product]);

        $expected = ['abc' => 'def'];
        $this->dependencyMocks['entityManager']->method('attachToItem')
            ->willReturn($expected);

        static::assertEquals($expected, $this->model->createOrderLineItem($this->container, $this->magentoQuote, $this->dataHolder));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Coordinator::class);

        $this->container = $this->mockFactory->create(Container::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->product = $this->mockFactory->create(Product::class);
    }
}
