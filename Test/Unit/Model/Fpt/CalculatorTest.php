<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Fpt;

use Klarna\Orderlines\Model\Fpt\Calculator;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Klarna\Base\Test\Unit\Mock\TestCase;

class CalculatorTest extends TestCase
{
    /**
     * @var Calculator
     */
    private $calculator;
    /**
     * @var ExtensibleDataInterface
     */
    private $data;

    public function testGetFptDataItemsAreInvalid(): void
    {
        $this->data->expects(static::once())
            ->method('getAllItems')
            ->willReturn([]);

        $this->dependencyMocks['validator']
            ->expects(static::never())
            ->method('isValidOrderItem');
        $this->dependencyMocks['validator']
            ->expects(static::never())
            ->method('isValidQuoteItem');

        $expected = [
            'tax' => 0,
            'name' => [],
            'reference' => []
        ];
        $result = $this->calculator->getFptData($this->data);

        static::assertSame($expected, $result);
    }

    public function testGetFptDataItemsWithAndWithoutAppliedWeeeTaxes(): void
    {
        $onlyMethods = ['getOrderItem'];
        $addMethods = ['getWeeeTaxAppliedRowAmount', 'getWeeeTaxApplied'];

        $cartItem1 = $this->mockFactory->create(InvoiceItem::class, $onlyMethods, $addMethods);
        $cartItem2 = $this->mockFactory->create(InvoiceItem::class, $onlyMethods, $addMethods);
        $cartItem3 = $this->mockFactory->create(InvoiceItem::class, $onlyMethods, $addMethods);
        $cartItem4 = $this->mockFactory->create(InvoiceItem::class, $onlyMethods, $addMethods);

        $this->data->expects(static::once())
            ->method('getAllItems')
            ->willReturn([$cartItem1, $cartItem2, $cartItem3, $cartItem4]);

        $this->dependencyMocks['validator']
            ->expects(static::exactly(4))
            ->method('isValidOrderItem')
            ->willReturn(true);
        $this->dependencyMocks['validator']
            ->expects(static::exactly(4))
            ->method('isValidQuoteItem')
            ->willReturn(true);

        $cartItem1->expects(static::once())
            ->method('getWeeeTaxAppliedRowAmount')
            ->willReturn(12);
        $cartItem2->expects(static::once())
            ->method('getWeeeTaxAppliedRowAmount')
            ->willReturn(21);
        $cartItem3->expects(static::once())
            ->method('getWeeeTaxAppliedRowAmount')
            ->willReturn(19);
        $cartItem4->expects(static::once())
            ->method('getWeeeTaxAppliedRowAmount')
            ->willReturn(14);

        $cartItem1->expects(static::once())
            ->method('getWeeeTaxApplied')
            ->willReturn('{"ref":{"title":"VAT"}}');
        $cartItem2->expects(static::once())
            ->method('getWeeeTaxApplied')
            ->willReturn('{"ref":{"no_title_here":""}}');
        $cartItem3->expects(static::once())
            ->method('getWeeeTaxApplied')
            ->willReturn('{"ref":{"title":""}}');
        $cartItem4->expects(static::once())
            ->method('getWeeeTaxApplied')
            ->willReturn('{"ref":{"title":"VAT", "title_2": "irrelevant"}}');

        $expected = [
            'tax' => 66,
            'name' => ['VAT'],
            'reference' => ['VAT']
        ];
        $result = $this->calculator->getFptData($this->data);

        static::assertSame($expected, $result);
    }

    protected function setup(): void
    {
        $this->calculator = parent::setUpMocks(Calculator::class);

        $this->data = $this->mockFactory->create(
            ExtensibleDataInterface::class,
            [],
            [
                'getAllItems'
            ]
        );
    }
}
