<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Shipping;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Shipping\Processor;
use Magento\Quote\Model\Quote;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Sales\Model\Order;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Shipping\Processor
 */
class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private Processor $processor;
    /**
     * @var DataHolder
     */
    private Dataholder $dataHolder;
    /**
     * @var Order
     */
    private Order $order;
    /**
     * @var Parameter
     */
    private Parameter $parameter;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;

    public function testProcessPrePurchaseSettingUnitPrice(): void
    {
        $expected = 7;
        $this->dependencyMocks['prePurchaseCalculator']->method('getUnitPrice')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingUnitPrice')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingTaxRate(): void
    {
        $expected = 7;
        $this->dependencyMocks['prePurchaseCalculator']->method('getTaxRate')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTaxRate')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingTotalAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['prePurchaseCalculator']->method('getTotalAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTotalAmount')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingTaxAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['prePurchaseCalculator']->method('getTaxAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTaxAmount')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingDiscountAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['prePurchaseCalculator']->method('getDiscountAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingDiscountAmount')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingTitle(): void
    {
        $expected = 'test';
        $this->dependencyMocks['prePurchaseCalculator']->method('getTitle')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTitle')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseSettingReference(): void
    {
        $expected = 'test';
        $this->dependencyMocks['prePurchaseCalculator']->method('getReference')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingReference')
            ->with($expected);
        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseCalculatingIncludingTax(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(false);
        $this->dependencyMocks['prePurchaseCalculator']->expects(static::once())
            ->method('calculateIncludedTaxData');

        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPrePurchaseCalculatingWithSeparatedTaxLine(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(true);
        $this->dependencyMocks['prePurchaseCalculator']->expects(static::once())
            ->method('calculateSeparateTaxLineData');

        $this->processor->processPrePurchase($this->dataHolder, $this->parameter, $this->magentoQuote);
    }

    public function testProcessPostPurchaseSettingUnitPrice(): void
    {
        $expected = 7;
        $this->dependencyMocks['postPurchaseCalculator']->method('getUnitPrice')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingUnitPrice')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingTaxRate(): void
    {
        $expected = 7;
        $this->dependencyMocks['postPurchaseCalculator']->method('getTaxRate')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTaxRate')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingTotalAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['postPurchaseCalculator']->method('getTotalAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTotalAmount')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingTaxAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['postPurchaseCalculator']->method('getTaxAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTaxAmount')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingDiscountAmount(): void
    {
        $expected = 7;
        $this->dependencyMocks['postPurchaseCalculator']->method('getDiscountAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingDiscountAmount')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingTitle(): void
    {
        $expected = 'test';
        $this->dependencyMocks['postPurchaseCalculator']->method('getTitle')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingTitle')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    public function testProcessPostPurchaseSettingReference(): void
    {
        $expected = 'test';
        $this->dependencyMocks['postPurchaseCalculator']->method('getReference')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setShippingReference')
            ->with($expected);
        $this->processor->processPostPurchase($this->dataHolder, $this->parameter, $this->order);
    }

    protected function setUp(): void
    {
        $this->processor = parent::setUpMocks(Processor::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->order = $this->mockFactory->create(Order::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);

        $this->magentoQuote = $this->mockFactory->create(Quote::class);
    }
}