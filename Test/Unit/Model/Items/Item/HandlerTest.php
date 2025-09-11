<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Item\Handler;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Handler
 */
class HandlerTest extends TestCase
{
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Parameter
     */
    private Parameter $parameter;
    /**
     * @var Handler
     */
    private Handler $model;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;
    /**
     * @var Order
     */
    private Order $magentoOrder;

    public function testCollectPrePurchaseNotCollectableImpliesNoCalculation(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')->willReturn(false);

        $this->dependencyMocks['iterator']->expects(static::never())
            ->method('getCalculatedItems');
        $this->model->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    public function testCollectPrePurchaseCollectableImpliesCalculationMethodIsCalled(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')->willReturn(true);

        $this->dependencyMocks['iterator']->expects(static::once())
            ->method('getCalculatedItems')
            ->willReturn([]);
        $this->model->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    public function testCollectPostPurchaseNotCollectableImpliesNoCalculation(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')->willReturn(false);

        $this->dependencyMocks['iterator']->expects(static::never())
            ->method('getCalculatedItems');
        $this->model->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testCollectPostPurchaseCollectableImpliesCalculationMethodIsCalled(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')->willReturn(true);

        $this->dependencyMocks['iterator']->expects(static::once())
            ->method('getCalculatedItems')
            ->willReturn([]);
        $this->model->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testFetchNotFetchableImpliesNoOrderLineItemIsAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(false);
        $this->parameter->method('getItems')
            ->willReturn([['foo' => 'bar']]);
        $this->parameter->expects(static::never())
            ->method('addOrderLine');

        $this->model->fetch($this->parameter);
    }

    public function testFetchFetchableImpliesOrderLineItemIsAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(true);
        $this->parameter->method('getItems')
            ->willReturn([['foo' => 'bar']]);
        $this->parameter->expects(static::once())
            ->method('addOrderLine');

        $this->model->fetch($this->parameter);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Handler::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoOrder = $this->mockFactory->create(Order::class);
    }
}