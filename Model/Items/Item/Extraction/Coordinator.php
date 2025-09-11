<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction;

use Klarna\Base\Model\Quote\Address\Country;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Item\Extraction\Calculator\ExclusiveTaxCalculator;
use Klarna\Orderlines\Model\Items\Item\Extraction\Calculator\InclusiveTaxCalculator;
use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\EntityManager;
use Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes\ProductCollection;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @internal
 */
class Coordinator
{
    /**
     * @var Country
     */
    private Country $country;
    /**
     * @var ExclusiveTaxCalculator
     */
    private ExclusiveTaxCalculator $exclusiveTaxCalculator;
    /**
     * @var InclusiveTaxCalculator
     */
    private InclusiveTaxCalculator $inclusiveTaxCalculator;
    /**
     * @var ProductCollection
     */
    private ProductCollection $productCollection;
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    /**
     * @param Country $country
     * @param ExclusiveTaxCalculator $exclusiveTaxCalculator
     * @param InclusiveTaxCalculator $inclusiveTaxCalculator
     * @param ProductCollection $productCollection
     * @param EntityManager $entityManager
     * @codeCoverageIgnore
     */
    public function __construct(
        Country $country,
        ExclusiveTaxCalculator $exclusiveTaxCalculator,
        InclusiveTaxCalculator $inclusiveTaxCalculator,
        ProductCollection $productCollection,
        EntityManager $entityManager
    ) {
        $this->country = $country;
        $this->exclusiveTaxCalculator = $exclusiveTaxCalculator;
        $this->inclusiveTaxCalculator = $inclusiveTaxCalculator;
        $this->productCollection = $productCollection;
        $this->entityManager = $entityManager;
    }

    /**
     * Creating the order line item
     *
     * @param Container $container
     * @param ExtensibleDataInterface $dataObject
     * @param DataHolder $dataHolder
     * @return array
     */
    public function createOrderLineItem(
        Container $container,
        ExtensibleDataInterface $dataObject,
        DataHolder $dataHolder
    ): array {
        if ($this->country->isUsCountry($dataObject)) {
            $item = $this->exclusiveTaxCalculator->getOrderLineItem($container);
        } else {
            $item = $this->inclusiveTaxCalculator->getOrderLineItem($container);
        }

        $this->productCollection->configure($dataHolder);
        $products = $this->productCollection->get($container);
        if (!empty($products)) {
            $item = $this->entityManager->attachToItem($item, array_shift($products));
        }

        return $item;
    }
}
