<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Giftcard;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Giftcard\Calculator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Address\Total;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Giftcard\Calculator
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
        static::assertEquals('my_title', $this->calculator->getTitle());
        static::assertEquals('my_code', $this->calculator->getReference());
    }

    public function testCalculateAmountIsNotZero(): void
    {
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->willReturn(5);
        $this->calculator->calculate($this->dataHolder);

        static::assertEquals(-5, $this->calculator->getUnitPrice());
        static::assertEquals(-5, $this->calculator->getTotalAmount());
        static::assertEquals(0, $this->calculator->getTaxRate());
        static::assertEquals(0, $this->calculator->getTaxAmount());
        static::assertEquals('my_title', $this->calculator->getTitle());
        static::assertEquals('my_code', $this->calculator->getReference());
    }

    protected function setUp(): void
    {
        $this->calculator = parent::setUpMocks(Calculator::class);

        $total = $this->mockFactory->create(Total::class, [], ['getTitle', 'getCode']);
        $total->method('getTitle')
            ->willReturn(__('my_title'));
        $total->method('getCode')
            ->willReturn('my_code');
        $totals = [
            'giftcardaccount' => $total
        ];
        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->dataHolder->method('getTotals')
            ->willReturn($totals);
    }
}
