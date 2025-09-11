<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction;

use Klarna\Orderlines\Model\Container\DataHolder;
use Magento\Catalog\Model\Product\Configuration\Item\ItemInterface;
use Klarna\Orderlines\Model\Items\Item\Extraction\ContainerFactory;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * @internal
 */
class Iterator
{
    /**
     * @var ItemValidator
     */
    private ItemValidator $itemValidator;
    /**
     * @var ContainerFactory
     */
    private ContainerFactory $containerFactory;
    /**
     * @var Coordinator
     */
    private Coordinator $coordinator;

    /**
     * @param ItemValidator $itemValidator
     * @param ContainerFactory $containerFactory
     * @param Coordinator $coordinator
     * @codeCoverageIgnore
     */
    public function __construct(
        ItemValidator $itemValidator,
        ContainerFactory $containerFactory,
        Coordinator $coordinator
    ) {
        $this->itemValidator = $itemValidator;
        $this->containerFactory = $containerFactory;
        $this->coordinator = $coordinator;
    }

    /**
     * Getting back the calculated orderline items
     *
     * @param DataHolder $dataHolder
     * @param ExtensibleDataInterface $dataObject
     * @return array
     */
    public function getCalculatedItems(DataHolder $dataHolder, ExtensibleDataInterface $dataObject): array
    {
        $items = [];
        foreach ($dataHolder->getItems() as $item) {
            if ($this->itemValidator->isBundledProductWithDynamicPriceType($item) ||
                $this->itemValidator->hasInvalidParentProduct($item)
            ) {
                continue;
            }

            /** @var Container $container */
            $container = $this->containerFactory->create();
            $container->setValues($item, $dataHolder->getStore());

            $items[] = $this->coordinator->createOrderLineItem($container, $dataObject, $dataHolder);
        }

        return $items;
    }
}
