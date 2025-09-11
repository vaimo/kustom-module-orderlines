<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Calculator;

use Klarna\Orderlines\Model\Calculator\Shipping;
use Klarna\Orderlines\Model\Container\DataHolder;
use Magento\Store\Model\Store;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote\Address;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Calculator\Shipping
 */
class ShippingTest extends TestCase
{
    /**
     * @var Shipping
     */
    private Shipping $model;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Address
     */
    private Address $shippingAddress;

    public function testGetTaxRateEmptySingleTaxRateEntryAndShippingAmountIsZeroFromTypeIntImpliesReturningZero(): void
    {
        $this->shippingAddress->method('getShippingAmount')
            ->willReturn(0);
        static::assertEquals(0, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateEmptySingleTaxRateEntryAndShippingAmountIsZeroFromTypeStringImpliesReturningZero(): void
    {
        $this->shippingAddress->method('getShippingAmount')
            ->willReturn('0');
        static::assertEquals(0, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateEmptySingleTaxRateEntryAndShippingAmountIsZeroWithDecimalValuesFromTypeStringImpliesReturningZero(): void
    {
        $this->shippingAddress->method('getShippingAmount')
            ->willReturn('0.00000');
        static::assertEquals(0, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateEmptySingleTaxRateEntryAndReturningCalculatedValue(): void
    {
        $this->shippingAddress->method('getBaseShippingAmount')
            ->willReturn(1);
        $this->shippingAddress->method('getBaseShippingTaxAmount')
            ->willReturn(0.19);
        static::assertEquals(19, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateByShippingCostsEmptySingleTaxRateEntryAndShippingCostsAreZero(): void
    {
        static::assertEquals(0, $this->model->getTaxRateByShippingCosts($this->shippingAddress, 0));
    }

    public function testGetTaxRateItemsAppliedTaxesKeySetButNotOtherKeysImpliesCalculatingTaxRate(): void
    {
        $this->shippingAddress->method('getData')
            ->willReturn(['items_applied_taxes' => []]);

        $this->shippingAddress->method('getBaseShippingAmount')
            ->willReturn(1);
        $this->shippingAddress->method('getBaseShippingTaxAmount')
            ->willReturn(0.19);
        static::assertEquals(19, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateItemsAppliedTaxesKeySetButNotShippingKeyImpliesCalculatingTaxRate(): void
    {
        $this->shippingAddress->method('getData')
            ->willReturn(
                [
                    'items_applied_taxes' =>
                        [
                            'a' =>
                                [
                                    [
                                        'percent' => 27
                                    ],
                                    [
                                        'percent' => 26
                                    ]
                                ]
                        ]
                ]
            );

        $this->shippingAddress->method('getBaseShippingAmount')
            ->willReturn(1);
        $this->shippingAddress->method('getBaseShippingTaxAmount')
            ->willReturn(0.19);
        static::assertEquals(19, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateItemsAppliedTaxesAndShippingKeyButSeveralRatesUsedImpliesCalculatingTaxRate(): void
    {
        $this->shippingAddress->method('getData')
            ->willReturn(
                [
                    'items_applied_taxes' =>
                        [
                            'shipping' =>
                                [
                                    [
                                        'percent' => 27
                                    ],
                                    [
                                        'percent' => 26
                                    ]
                                ]
                        ]
                ]
            );

        $this->shippingAddress->method('getBaseShippingAmount')
            ->willReturn(1);
        $this->shippingAddress->method('getBaseShippingTaxAmount')
            ->willReturn(0.19);
        static::assertEquals(19, $this->model->getTaxRate($this->dataHolder));
    }

    public function testGetTaxRateItemsAppliedTaxesAndShippingKeyAndJustOneRateUsedImpliesReturningStoredTaxRate(): void
    {
        $this->shippingAddress->method('getData')
            ->willReturn(
                [
                    'items_applied_taxes' =>
                    [
                        'shipping' =>
                            [
                                [
                                    'percent' => 27
                                ]
                            ]
                    ]
                ]
            );

        $this->shippingAddress->method('getBaseShippingAmount')
            ->willReturn(1);
        $this->shippingAddress->method('getBaseShippingTaxAmount')
            ->willReturn(0.19);
        static::assertEquals(27, $this->model->getTaxRate($this->dataHolder));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Shipping::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->shippingAddress = $this->mockFactory->create(
            Address::class,
            [
                'getData'
            ],
            [
                'getShippingAmount',
                'getBaseShippingInclTax',
                'getBaseShippingAmount',
                'getBaseShippingTaxAmount'
            ]
        );
        $this->dataHolder->method('getShippingAddress')
            ->willReturn($this->shippingAddress);
    }
}