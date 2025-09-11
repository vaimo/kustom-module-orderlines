<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Tax;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Tax\Calculator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Store\Model\Store;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Tax\Calculator
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

    public function testCalculateNoFptUsed(): void
    {
        $this->dataHolder->method('getTotalTax')
            ->willReturn(0);
        $this->dependencyMocks['validator']->method('isFptUsable')
            ->willReturn(false);
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->with(0)
            ->willReturn(0);
        $this->calculator->calculate($this->dataHolder);

        static::assertEquals(0, $this->calculator->getUnitPrice());
        static::assertEquals(0, $this->calculator->getTotalAmount());
        static::assertEquals(0, $this->calculator->getTaxRate());
        static::assertEquals(0, $this->calculator->getTaxAmount());
        static::assertEquals('', $this->calculator->getTitle());
        static::assertEquals('', $this->calculator->getReference());
    }

    public function testCalculateFptUsed(): void
    {
        $this->dataHolder->method('getTotalTax')
            ->willReturn(0);
        $this->dependencyMocks['validator']->method('isFptUsable')
            ->willReturn(true);
        $this->dataHolder->method('getFptTax')
            ->willReturn(['tax' => 2]);
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->with(2)
            ->willReturn(5);
        $this->calculator->calculate($this->dataHolder);

        static::assertEquals(5, $this->calculator->getUnitPrice());
        static::assertEquals(0, $this->calculator->getTotalAmount());
        static::assertEquals(0, $this->calculator->getTaxRate());
        static::assertEquals(5, $this->calculator->getTaxAmount());
        static::assertEquals('', $this->calculator->getTitle());
        static::assertEquals('', $this->calculator->getReference());
    }

    protected function setUp(): void
    {
        $this->calculator = parent::setUpMocks(Calculator::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);

        $store = $this->mockFactory->create(Store::class);
        $this->dataHolder->method('getStore')
            ->willReturn($store);
    }
}
