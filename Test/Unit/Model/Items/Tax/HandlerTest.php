<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Tax;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Tax\Handler;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Tax\Handler
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
    private Handler $handler;
    /**
     * @var Quote
     */
    private Quote $quote;
    /**
     * @var Order
     */
    private Order $order;

    public function testCollectPrePurchaseNotCollectableAndWillNotBeProcessed(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects(static::never())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->quote);
    }

    public function testCollectPrePurchaseCollectableAndWillBeProcessed(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects(static::once())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->quote);
    }

    public function testCollectPostPurchaseNotCollectableAndWillNotBeProcessed(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects(static::never())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->order);
    }

    public function testCollectPostPurchaseCollectableAndWillBeProcessed(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects(static::once())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->order);
    }

    public function testFetchNotFetchableAndNoOrderLineItemWillBeAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(false);
        $this->parameter->expects(static::never())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    public function testFetchFetchableAndOrderLineItemWillBeAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(true);
        $this->parameter->expects(static::once())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    protected function setUp(): void
    {
        $this->handler = parent::setUpMocks(Handler::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
        $this->quote = $this->mockFactory->create(Quote::class);
        $this->order = $this->mockFactory->create(Order::class);
    }
}