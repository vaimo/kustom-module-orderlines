<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Customerbalance;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Customerbalance\Calculator;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Customerbalance\Calculator
 */
class CalculatorTest extends TestCase
{
    /**
     * @var Calculator
     */
    private Calculator $calculator;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;

    public function testCalculateAmountIsZero(): void
    {
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->willReturn(0);
        $this->calculator->calculate($this->dataHolder);

        static::assertEquals(0, $this->calculator->getUnitPrice());
        static::assertEquals(0, $this->calculator->getTotalAmount());
        static::assertEquals(0, $this->calculator->getTaxRate());
        static::assertEquals(0, $this->calculator->getTaxAmount());
        static::assertEquals('Customer Balance', $this->calculator->getTitle());
        static::assertEquals('customerbalance', $this->calculator->getReference());
    }

    public function testCalculateAmountIsNotZero(): void
    {
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->willReturn(-5);
        $this->calculator->calculate($this->dataHolder);

        static::assertEquals(-5, $this->calculator->getUnitPrice());
        static::assertEquals(-5, $this->calculator->getTotalAmount());
        static::assertEquals(0, $this->calculator->getTaxRate());
        static::assertEquals(0, $this->calculator->getTaxAmount());
        static::assertEquals('Customer Balance', $this->calculator->getTitle());
        static::assertEquals('customerbalance', $this->calculator->getReference());
    }

    protected function setUp(): void
    {
        $this->calculator = parent::setUpMocks(Calculator::class);
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
    }
}
