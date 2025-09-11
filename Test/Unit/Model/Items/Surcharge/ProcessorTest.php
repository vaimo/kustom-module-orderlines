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
use Klarna\Orderlines\Model\Items\Surcharge\Processor;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Surcharge\Processor
 */
class ProcessorTest extends TestCase
{
    /**
     * @var Processor
     */
    private Processor $processor;
    /**
     * @var Parameter
     */
    private Parameter $parameter;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;

    public function testProcessSettingSurchargeUnitPrice(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getUnitPrice')
            ->willReturn($expected);
        $this->parameter->expects($this->once())
            ->method('setSurchargeUnitPrice')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingTotalAmount(): void
    {
        $expected = 100;
        $this->dependencyMocks['calculator']->method('getTotalAmount')
            ->willReturn($expected);
        $this->parameter->expects($this->once())
            ->method('setSurchargeTotalAmount')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingReference(): void
    {
        $expected = 'abc';
        $this->dependencyMocks['calculator']->method('getReference')
            ->willReturn($expected);
        $this->parameter->expects($this->once())
            ->method('setSurchargeReference')
            ->with($expected);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingName(): void
    {
        $expected = 'abc';
        $this->dependencyMocks['calculator']->method('getTitle')
            ->willReturn($expected);
        $this->parameter->expects($this->once())
            ->method('setSurchargeName')
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
