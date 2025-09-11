<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Calculator;

use Klarna\AdminSettings\Model\Configurations\ShippingOptions;
use Klarna\Orderlines\Model\Calculator\ItemShippingAttributes\Provider;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;

/**
 * Calculating shipping attributes for the order creation and update
 *
 * @deprecated Will be removed in the next major version
 * @see Provider
 * @api
 */
class ItemShippingAttributes
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var ShippingOptions
     */
    private ShippingOptions $shippingOptions;
    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ShippingOptions      $shippingOptions
     * @param Provider|null        $provider
     * @codeCoverageIgnore
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ShippingOptions $shippingOptions,
        ?Provider $provider = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->shippingOptions = $shippingOptions;
        $this->provider = $provider ?: ObjectManager::getInstance()->get(Provider::class);
    }

    /**
     * Wrapper for the Provider
     *
     * @param array            $itemResult
     * @param ProductInterface $product
     * @return array
     */
    public function addShippingAttributes(array $itemResult, ProductInterface $product): array
    {
        return $this->provider->addShippingAttributes($itemResult, $product);
    }
}
