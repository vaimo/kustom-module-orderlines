<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Item;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Item\Validator;
use Klarna\Base\Test\Unit\Mock\TestCase;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Item\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private Validator $model;
    /**
     * @var DataHolder
     */
    private DataHolder $dataHolder;
    /**
     * @var Parameter
     */
    private Parameter $parameter;

    public function testIsCollectableItemCountIsZeroImpliesReturnsFalse(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn([]);
        static::assertFalse($this->model->isCollectable($this->dataHolder));
    }

    public function testIsCollectableItemCountIsNotZeroImpliesReturnsTrue(): void
    {
        $this->dataHolder->method('getItems')
            ->willReturn(['a']);
        static::assertTrue($this->model->isCollectable($this->dataHolder));
    }

    public function testIsFetchableItemCountIsZeroImpliesReturnsFalse(): void
    {
        $this->parameter->method('getItems')
            ->willReturn([]);
        static::assertFalse($this->model->isFetchable($this->parameter));
    }

    public function testIsFetchableItemCountIsNotZeroImpliesReturnsTrue(): void
    {
        $this->parameter->method('getItems')
            ->willReturn(['a']);
        static::assertTrue($this->model->isFetchable($this->parameter));
    }

    protected function setUp(): void
    {
        $this->model = parent::setUpMocks(Validator::class);

        $this->dataHolder = $this->mockFactory->create(DataHolder::class);
        $this->parameter = $this->mockFactory->create(Parameter::class);
    }
}
