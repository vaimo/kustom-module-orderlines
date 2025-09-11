<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */

namespace Klarna\Orderlines\Test\Unit\Model\Items\Tax;

use Klarna\Orderlines\Model\Items\Tax\Validator;
use Klarna\Base\Test\Unit\Mock\TestCase;
use Magento\Quote\Model\Quote;

/**
 * @coversDefaultClass \Klarna\Orderlines\Model\Items\Tax\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var Quote
     */
    private Quote $magentoQuote;

    public function testIsCollectableConfigurationReturnsTrue(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(true);
        static::assertTrue($this->validator->isCollectable($this->magentoQuote));
    }

    public function testIsCollectableConfigurationReturnsFalse(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(false);
        static::assertFalse($this->validator->isCollectable($this->magentoQuote));
    }

    public function testIsFetchableConfigurationReturnsTrue(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(true);
        $this->validator->isCollectable($this->magentoQuote);
        static::assertTrue($this->validator->isFetchable());
    }

    public function testIsFetchableConfigurationReturnsFalse(): void
    {
        $this->dependencyMocks['country']->method('isUsCountry')
            ->willReturn(false);
        $this->validator->isCollectable($this->magentoQuote);
        static::assertFalse($this->validator->isFetchable());
    }

    protected function setUp(): void
    {
        $this->validator = parent::setUpMocks(Validator::class);
        $this->magentoQuote = $this->mockFactory->create(Quote::class);
    }
}