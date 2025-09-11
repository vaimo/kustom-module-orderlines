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
use Klarna\Orderlines\Model\Items\Reward\Processor;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Reward\Processor
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
    private DataHolder $dataHolder;
    /**
     * @var Parameter
     */
    private Parameter $parameter;

    public function testProcessSettingRewardUnitPrice(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getUnitPrice')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardUnitPrice')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingRewardTaxRate(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getTaxRate')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardTaxRate')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingRewardTotalAmount(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getTotalAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardTotalAmount')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingRewardTaxAmount(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getTaxAmount')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardTaxAmount')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingRewardTitle(): void
    {
        $expected = 'abc';
        $this->dependencyMocks['calculator']->method('getTitle')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardTitle')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingRewardReference(): void
    {
        $expected = 'abc';
        $this->dependencyMocks['calculator']->method('getReference')
            ->willReturn($expected);
        $this->parameter->expects(static::once())
            ->method('setRewardReference')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    protected function setUp(): void
    {
        $this->processor = parent::setUpMocks(Processor::class);

        $this->parameter = $this->mockFactory->create(Parameter::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
    }
}