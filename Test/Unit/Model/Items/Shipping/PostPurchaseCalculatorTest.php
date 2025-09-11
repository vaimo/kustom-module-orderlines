<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Shipping;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Shipping\PostPurchaseCalculator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Address;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Shipping\PostPurchaseCalculator
 */
class PostPurchaseCalculatorTest extends TestCase
{
    /**
     * @var PostPurchaseCalculator
     */
    private PostPurchaseCalculator $calculator;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Order
     */
    private Order $order;

    public function testCalculateSettingUnitPrice(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals(599, $this->calculator->getUnitPrice());
    }

    public function testCalculateSettingTaxRate(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals(700, $this->calculator->getTaxRate());
    }

    public function testCalculateSettingTotalAmount(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals(599, $this->calculator->getTotalAmount());
    }

    public function testCalculateSettingTaxAmount(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals(900, $this->calculator->getTaxAmount());
    }

    public function testCalculateSettingTitle(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals('Shipping & Handling (my_shipping_description)', $this->calculator->getTitle());
    }

    public function testCalculateSettingEmptyReference(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals('', $this->calculator->getReference());
    }

    public function testCalculateSettingNotEmptyReference(): void
    {
        $expected = 'my_shipping_method';
        $this->order->method('getShippingMethod')
            ->willReturn($expected);
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals($expected, $this->calculator->getReference());
    }

    public function testCalculateSettingDiscountAmount(): void
    {
        $this->calculator->calculate($this->dataHolder, $this->order);
        static::assertEquals(0, $this->calculator->getDiscountAmount());
    }

    protected function setUp(): void
    {
        $this->calculator = parent::setUpMocks(PostPurchaseCalculator::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->order = $this->mockFactory->create(Order::class);

        $address = $this->mockFactory->create(Address::class);
        $this->dataHolder->setShippingAddress($address);

        $this->dataHolder->method('getBaseShippingInclTax')
            ->willReturn(5.99);
        $this->dependencyMocks['calculator']->method('getTaxRate')
            ->willReturn(7.0);
        $this->order->method('getShippingTaxAmount')
            ->willReturn(9);
        $this->order->method('getShippingDescription')
            ->willReturn('my_shipping_description');
        $this->dependencyMocks['dataConverter']
            ->method('toApiFloat')
            ->willReturnCallback(fn($float) =>
                match($float) {
                    5.99 => 599,
                    7.0 => 700,
                    9 => 900
                }
            );
    }
}