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
use Klarna\Orderlines\Model\Items\Tax\Processor;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Tax\Processor
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

    public function testProcessSettingTaxUnitPrice(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getUnitPrice')
            ->willReturn(123);
        $this->parameter->expects(static::once())
            ->method('setTaxUnitPrice')
            ->with($value);

        $this->processor->process($this->dataHolder, $this->parameter);
    }

    public function testProcessSettingTotalTaxAmount(): void
    {
        $value = 123;
        $this->dependencyMocks['calculator']->method('getTaxAmount')
            ->willReturn(123);
        $this->parameter->expects(static::once())
            ->method('setTaxTotalAmount')
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