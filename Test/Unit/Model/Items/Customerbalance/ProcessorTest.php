<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Customerbalance;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Customerbalance\Processor;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Customerbalance\Processor
 */
class ProcessorTest extends TestCase
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
     * @var Processor
     */
    private Processor $processor;

    public function testProcessSettingCustomerBalanceUnitPrice(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getUnitPrice')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceUnitPrice')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingCustomerBalanceTaxRate(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getTaxRate')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceTaxRate')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingCustomerBalanceTotalAmount(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getTotalAmount')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceTotalAmount')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingCustomerBalanceTaxAmount(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getTaxAmount')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceTaxAmount')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingCustomerBalanceTitle(): void
    {
        $value = 'my output';
        $this->dependencyMocks['calculator']->method('getTitle')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceTitle')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingCustomerBalanceReference(): void
    {
        $value = 'my output';
        $this->dependencyMocks['calculator']->method('getReference')
            ->willReturn($value);
        $this->parameter->expects(static::once())
            ->method('setCustomerBalanceReference')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    protected function setUp(): void
    {
        $this->processor = parent::setUpMocks(Processor::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
    }
}