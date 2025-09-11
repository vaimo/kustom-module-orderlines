<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item\Extraction;

use Klarna\Orderlines\Model\Items\Item\Extraction\Calculator\ExclusiveTaxCalculator;
use Klarna\Orderlines\Model\Items\Item\Extraction\Container;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Extraction\Calculator\ExclusiveTaxCalculator
 */
class ExclusiveTaxCalculatorTest extends TestCase
{
    /**
     * @var ExclusiveTaxCalculator 
     */
    private ExclusiveTaxCalculator $model;
    /**
     * @var Container 
     */
    private Container $container;

    public function testGetOrderLineItemCheckingUnitPrice(): void
    {
        $this->container->method('getRowTotal')
            ->willReturn((float) 50);
        $this->container->method('getDiscountAmount')
            ->willReturn((float) 10);
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->willReturnCallback(fn($float) =>
                match($float) {
                    (float) 50 => (float) 50,
                    (float) 40 => 40
                }
            );

        $result = $this->model->getOrderLineItem($this->container);
        static::assertEquals(50, $result['unit_price']);
    }

    public function testGetOrderLineItemCheckingTotalAmount(): void
    {
        $this->container->method('getRowTotal')
            ->willReturn((float) 50);
        $this->container->method('getDiscountAmount')
            ->willReturn((float) 10);
        $this->dependencyMocks['dataConverter']->method('toApiFloat')
            ->willReturnCallback(fn($float) =>
                match($float) {
                    (float) 50 => (float) 50,
                    (float) 40 => 40
                }
            );

        $result = $this->model->getOrderLineItem($this->container);
        static::assertEquals(40, $result['total_amount']);
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(ExclusiveTaxCalculator::class);
        $this->container = $this->mockFactory->create(Container::class);

        $this->dependencyMocks['baseResult']->method('getFromContainer')
            ->willReturn(['quantity' => 1]);
    }
}