<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Shipping;

use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;

/**
 * @internal
 */
class Validator
{

    /**
     * Returns true if it can be collected for the pre purchase
     *
     * @param DataHolder $dataHolder
     * @param Parameter $parameter
     * @return bool
     */
    public function isCollectableForPrePurchase(DataHolder $dataHolder, Parameter $parameter): bool
    {
        return $parameter->isShippingLineEnabled() &&
            !$dataHolder->isVirtual() &&
            !empty($dataHolder->getShippingAddress()) &&
            isset($dataHolder->getTotals()['shipping']);
    }

    /**
     * Returns true if it can be collected for the post purchase
     *
     * @param DataHolder $orderDetails
     * @return bool
     */
    public function isCollectableForPostPurchase(DataHolder $orderDetails): bool
    {
        return !$orderDetails->isVirtual() && !empty($orderDetails->getShippingAddress());
    }

    /**
     * Returns true if its fetchable
     *
     * @param Parameter $parameter
     * @return bool
     */
    public function isFetchable(Parameter $parameter): bool
    {
        return !$parameter->isVirtual() && $parameter->isShippingLineEnabled();
    }
}
