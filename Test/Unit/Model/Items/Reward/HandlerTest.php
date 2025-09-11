<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Items\Reward;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Reward\Handler;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Reward\Handler
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
    private Quote $magentoQuote;
    /**
     * @var Order
     */
    private Order $magentoOrder;

    public function testFetchNotFetchableAndThereforeNoOrderLineAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(false);
        $this->parameter->expects(static::never())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    public function testFetchFetchableAndThereforeNoOrderLineAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(true);
        $this->parameter->expects(static::once())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    public function testCollectPostPurchaseNotCollectableImpliesNoProcessingOfTheItems(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects(static::never())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testCollectPostPurchaseCollectableImpliesProcessingOfTheItems(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects(static::once())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testCollectPrePurchaseNotCollectableImpliesNoProcessingOfTheItems(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects(static::never())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    public function testCollectPrePurchaseCollectableImpliesProcessingOfTheItems(): void
    {
        $this->dependencyMocks['validator']->method('isCollectable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects(static::once())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    protected function setUp(): void
    {
        $this->handler = parent::setUpMocks(Handler::class);

        $this->parameter = $this->mockFactory->create(Parameter::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoOrder = $this->mockFactory->create(Order::class);
    }
}