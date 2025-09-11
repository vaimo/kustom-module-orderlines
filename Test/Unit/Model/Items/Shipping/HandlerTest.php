<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Items\Shipping;

use Klarna\Base\Test\Unit\Mock\TestCase;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Shipping\Handler;
use Magento\Sales\Model\Order;
use Magento\Quote\Model\Quote;

class HandlerTest extends TestCase
{
    /**
     * @var Handler
     */
    private Handler $handler;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Parameter
     */
    private Parameter $parameter;
    /**
     * @var Quote
     */
    private Quote $quote;
    /**
     * @var Order
     */
    private Order $order;

    public function testCollectPrePurchaseNotCollectableImpliesNotProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isCollectableForPrePurchase')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects($this->never())
            ->method('processPrePurchase');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->quote);
    }

    public function testCollectPrePurchaseCollectableImpliesProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isCollectableForPrePurchase')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects($this->once())
            ->method('processPrePurchase');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->quote);
    }

    public function testCollectPostPurchaseNotCollectableImpliesNotProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isCollectableForPostPurchase')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects($this->never())
            ->method('processPostPurchase');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->order);
    }

    public function testCollectPostPurchaseCollectableImpliesProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isCollectableForPostPurchase')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects($this->once())
            ->method('processPostPurchase');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->order);
    }

    public function testFetchNotFetchableImpliesNotProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(false);
        $this->parameter->expects($this->never())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    public function testFetchFetchableImpliesProcessingData(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(true);
        $this->parameter->expects($this->once())
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