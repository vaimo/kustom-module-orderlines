<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Test\Unit\Model\Fpt;

use Magento\Store\Api\Data\StoreInterface;
use Klarna\Orderlines\Model\Fpt\Validator;
use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Store\Model\Store;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Sales\Model\Order\Creditmemo\Item as CreditMemoItem;
use Magento\Sales\Model\Order\Invoice\Item as InvoiceItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Sales\Api\Data\OrderItemInterface;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var Store
     */
    private $store;
    /**
     * @var ExtensibleDataInterface
     */
    private $data;
    /**
     * @var InvoiceItem
     */
    private $invoiceItem;
    /**
     * @var CreditMemoItem
     */
    private $creditMemoItem;
    /**
     * @var OrderItem
     */
    private $item;
    /**
     * @var OrderItemInterface
     */
    private $order;

    public function testIsFptUsableTrue(): void
    {
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('value');
        $this->dependencyMocks['scopeConfig']->method('isSetFlag')
            ->willReturn(true);
        static::assertTrue($this->validator->isFptUsable($this->store));
    }

    public function testIsFptUsableFlagNotSet(): void
    {
        $this->dependencyMocks['scopeConfig']->method('isSetFlag')
            ->willReturn(false);
        static::assertFalse($this->validator->isFptUsable($this->store));
    }

    public function testIsFptUsableGetValueReturnsNull(): void
    {
        $this->dependencyMocks['scopeConfig']->method('isSetFlag')
            ->willReturn(true);
        $this->dependencyMocks['scopeConfig']->method('getValue')
            ->willReturn('');
        static::assertFalse($this->validator->isFptUsable($this->store));
    }

    public function testIsValidOrderItemIsNotCorrectInstance(): void
    {
        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->item));
    }

    public function testIsValidOrderItemParentItemNull(): void
    {
        $this->creditMemoItem->method('getOrderItem')
            ->willReturn($this->item);
        $this->item->method('getParentItem')
            ->willReturn(null);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemGetParentItemByIDReturnsNull(): void
    {
        $this->creditMemoItem->method('getOrderItem')
            ->willReturn($this->item);
        $this->item->method('getParentItem')
            ->willReturn($this->order);
        $this->item->method('getParentItemId')
            ->willReturn(null);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsNonBundleProduct(): void
    {
        $this->creditInvoiceItemValidHelper();

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(true);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsBundledProductWithFixedPriceType(): void
    {
        $this->creditInvoiceItemValidHelper();

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturn(true);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsBundledProductWithDynamicPriceType(): void
    {
        $this->creditInvoiceItemValidHelper();

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithDynamicPriceType')
            ->willReturn(true);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsBundledProductWithDynamicPriceTypeParentItem(): void
    {
        $this->creditInvoiceItemValidHelper();

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']
            ->method('isBundledProductWithDynamicPriceType')
            ->willReturnOnConsecutiveCalls(false, true);

        static::assertFalse($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsValidCreditMemo(): void
    {
        $this->creditInvoiceItemValidHelper();

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturnOnConsecutiveCalls(false, false);

        static::assertTrue($this->validator->isValidOrderItem($this->data, $this->creditMemoItem));
    }

    public function testIsValidOrderItemIsValidInvoice(): void
    {
        $this->invoiceItem->method('getOrderItem')
            ->willReturn($this->item);
        $this->item->method('getParentItem')
            ->willReturn($this->order);
        $this->item->method('getParentItemId')
            ->willReturn('parent_item_id');
        $this->data->method('getItemById')
            ->willReturn($this->invoiceItem);

        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isNonBundleProduct')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']->expects(static::once())
            ->method('isBundledProductWithFixedPriceType')
            ->willReturn(false);
        $this->dependencyMocks['productTypeChecker']
            ->method('isBundledProductWithFixedPriceType')
            ->willReturnOnConsecutiveCalls(false, false);

        static::assertTrue($this->validator->isValidOrderItem($this->data, $this->invoiceItem));
    }

    private function creditInvoiceItemValidHelper(): void
    {
        $this->creditMemoItem->method('getOrderItem')
            ->willReturn($this->item);
        $this->item->method('getParentItem')
            ->willReturn($this->order);
        $this->item->method('getParentItemId')
            ->willReturn('parent_item_id');
        $this->data->method('getItemById')
            ->willReturn($this->creditMemoItem);
    }

    protected function setup(): void
    {
        $this->validator = parent::setUpMocks(Validator::class);

        $this->store = $this->mockFactory->create(StoreInterface::class);
        $this->order = $this->mockFactory->create(OrderItemInterface::class);

        $this->data = $this->mockFactory->create(
            ExtensibleDataInterface::class,
            [],
            [
                'getItemById'
            ]
        );
        $creditAndInvoiceItemMockMethods = ['getOrderItem'];
        $this->creditMemoItem = $this->mockFactory->create(CreditMemoItem::class, $creditAndInvoiceItemMockMethods);
        $this->invoiceItem = $this->mockFactory->create(InvoiceItem::class, $creditAndInvoiceItemMockMethods);

        $this->item = $this->mockFactory->create(OrderItem::class, [
            'getParentItem',
            'getParentItemId'
        ]);
    }
}
