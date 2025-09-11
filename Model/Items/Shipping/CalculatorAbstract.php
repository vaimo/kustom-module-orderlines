<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Shipping;

/**
 * @internal
 */
abstract class CalculatorAbstract extends \Klarna\Orderlines\Model\Items\CalculatorAbstract
{
    /**
     * @var int
     */
    private int $discountAmount = 0;

    /**
     * Getting back the discount amount
     *
     * @return int
     */
    public function getDiscountAmount(): int
    {
        return $this->discountAmount;
    }

    /**
     * Setting the discount amount
     *
     * @param int $discountAmount
     * @return $this
     */
    public function setDiscountAmount(int $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }
}
