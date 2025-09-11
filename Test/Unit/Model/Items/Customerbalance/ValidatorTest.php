<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Customerbalance;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Customerbalance\Validator;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Customerbalance\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Parameter
     */
    private Parameter $parameter;

    public function testIsCollectableCustomerBalanceAmountIsZeroAndMethodReturnsFalse(): void
    {
        $this->dataHolder->method('getUsedCustomerBalanceAmount')
            ->willReturn(0);

        static::assertFalse($this->validator->isCollectable($this->dataHolder));
    }

    public function testIsCollectableCustomerBalanceAmountIsGreaterThanZeroAndMethodReturnsTrue(): void
    {
        $this->dataHolder->method('getUsedCustomerBalanceAmount')
            ->willReturn(1);

        static::assertTrue($this->validator->isCollectable($this->dataHolder));
    }

    public function testIsFetchableCustomerBalanceAmountIsZeroAndMethodReturnsFalse(): void
    {
        $this->parameter->method('getCustomerbalanceTotalAmount')
            ->willReturn(0);

        static::assertFalse($this->validator->isFetchable($this->parameter));
    }

    public function testIsFetchableCustomerBalanceAmountIsGreaterThanZeroAndMethodReturnsTrue(): void
    {
        $this->parameter->method('getCustomerbalanceTotalAmount')
            ->willReturn(1);

        static::assertTrue($this->validator->isFetchable($this->parameter));
    }

    protected function setUp(): void
    {
        $this->validator = parent::setUpMocks(Validator::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
    }
}