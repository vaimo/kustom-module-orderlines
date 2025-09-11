<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Items\Surcharge;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Surcharge\Handler;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Surcharge\Handler
 */
class HandlerTest extends TestCase
{
    /**
     * @var Handler
     */
    private Handler $handler;
    /**
     * @var Parameter
     */
    private Parameter $parameter;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;
    /**
     * @var Order
     */
    private Order $magentoOrder;

    public function testCollectPrePurchaseNotUsableAndThereforeNotProcessable(): void
    {
        $this->dependencyMocks['fptValidator']->method('isFptUsable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects($this->never())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    public function testCollectPrePurchaseUsableAndThereforeProcessable(): void
    {
        $this->dependencyMocks['fptValidator']->method('isFptUsable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects($this->once())
            ->method('process');

        $this->handler->collectPrePurchase($this->parameter, $this->dataHolder, $this->magentoQuote);
    }

    public function testCollectPostPurchaseNotUsableAndThereforeNotProcessable(): void
    {
        $this->dependencyMocks['fptValidator']->method('isFptUsable')
            ->willReturn(false);
        $this->dependencyMocks['processor']->expects($this->never())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testCollectPostPurchaseUsableAndThereforeProcessable(): void
    {
        $this->dependencyMocks['fptValidator']->method('isFptUsable')
            ->willReturn(true);
        $this->dependencyMocks['processor']->expects($this->once())
            ->method('process');

        $this->handler->collectPostPurchase($this->parameter, $this->dataHolder, $this->magentoOrder);
    }

    public function testFetchNotFetchableImpliesNoOrderlineItemAdded(): void
    {
        $this->dependencyMocks['validator']->method('isFetchable')
            ->willReturn(false);
        $this->parameter->expects($this->never())
            ->method('addOrderLine');

        $this->handler->fetch($this->parameter);
    }

    public function testFetchFetchableImpliesOrderlineItemAdded(): void
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
        
        $this->parameter = $this->mockFactory->create(Parameter::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
        $this->magentoOrder = $this->mockFactory->create(Order::class);

        $store = $this->mockFactory->create(Store::class);
        $this->dataHolder->method('getStore')
            ->willReturn($store);
    }
}
