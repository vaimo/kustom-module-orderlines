<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Tax;

use Klarna\Base\Model\Quote\Address\Country;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @internal
 */
class Validator
{
    /**
     * @var Country
     */
    private Country $country;
    /**
     * @var bool
     */
    private bool $isUsable = false;

    /**
     * @param Country $country
     * @codeCoverageIgnore
     */
    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * Returns true if its collectable
     *
     * @param ExtensibleDataInterface $dataObject
     * @return bool
     */
    public function isCollectable(ExtensibleDataInterface $dataObject): bool
    {
        $this->isUsable = $this->country->isUsCountry($dataObject);
        return $this->isUsable;
    }

    /**
     * Returns true if its fetchable
     *
     * @return bool
     */
    public function isFetchable(): bool
    {
        return $this->isUsable;
    }
}
