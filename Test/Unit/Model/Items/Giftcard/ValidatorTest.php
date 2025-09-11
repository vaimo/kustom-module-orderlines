<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Giftcard;

use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Giftcard\Validator;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Giftcard\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private Validator $model;
    /**
     * @var Parameter
     */
    private Parameter $parameter;

    public function testIsFetchableGiftcardAmountIsPositive()
    {
        $this->parameter->method('getGiftCardAccountTotalAmount')
            ->willReturn(1);

        static::assertTrue($this->model->isFetchable($this->parameter));
    }

    public function testIsFetchableGiftcardAmountIsNegative()
    {
        $this->parameter->method('getGiftCardAccountTotalAmount')
            ->willReturn(-1);

        static::assertTrue($this->model->isFetchable($this->parameter));
    }

    public function testIsFetchableGiftcardAmountIsZero()
    {
        $this->parameter->method('getGiftCardAccountTotalAmount')
            ->willReturn(0);

        static::assertFalse($this->model->isFetchable($this->parameter));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Validator::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
    }
}
