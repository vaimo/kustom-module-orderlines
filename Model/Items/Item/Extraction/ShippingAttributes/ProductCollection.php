<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction\ShippingAttributes;

use Klarna\AdminSettings\Model\Configurations\ShippingOptions;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Items\Item\Extraction\Container;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * @internal
 */
class ProductCollection //TODO: Check if the whole logic of this class is really needed
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;
    /**
     * @var ShippingOptions
     */
    private ShippingOptions $shippingOptions;
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ShippingOptions $shippingOptions
     * @codeCoverageIgnore
     */
    public function __construct(CollectionFactory $collectionFactory, ShippingOptions $shippingOptions)
    {
        $this->collectionFactory = $collectionFactory;
        $this->shippingOptions = $shippingOptions;
    }

    /**
     * Configuring the collection
     *
     * @param DataHolder $dataHolder
     */
    public function configure(DataHolder $dataHolder): void
    {
        $ids = [];
        foreach ($dataHolder->getItems() as $item) {
            $ids[] = $item->getProductId();
        }
        $store = $dataHolder->getStore();

        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect($this->shippingOptions->getProductLengthAttribute($store));
        $collection->addAttributeToSelect($this->shippingOptions->getProductWidthAttribute($store));
        $collection->addAttributeToSelect($this->shippingOptions->getProductHeightAttribute($store));
        $collection->addAttributeToSelect('weight');
        $collection->addFieldToFilter('entity_id', ['in' => $ids]);

        $this->collection = $collection;
    }

    /**
     * Getting back the collection of the product
     *
     * @param Container $container
     * @return array
     */
    public function get(Container $container): array
    {
        if ($container->isProductCustomizable()) {
            // We want to use the main product SKU as the selected custom option
            // might have it's own SKU which doesn't map to an actual product
            $sku = $container->getProduct()->getData('sku');
        } else {
            $sku = $container->getSku();
        }

        return $this->collection->getItemsByColumnValue('sku', $sku);
    }
}
