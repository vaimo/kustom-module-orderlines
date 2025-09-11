<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item\Extraction\Calculator;

use Klarna\Base\Helper\DataConverter;
use Klarna\Orderlines\Model\ItemGenerator;
use Klarna\Orderlines\Model\Items\Item\Extraction\Container;

/**
 * @internal
 */
class BaseResult
{
    /**
     * @var DataConverter
     */
    private DataConverter $dataConverter;

    /**
     * @param DataConverter $dataConverter
     * @codeCoverageIgnore
     */
    public function __construct(DataConverter $dataConverter)
    {
        $this->dataConverter = $dataConverter;
    }

    /**
     * Getting back the base result
     *
     * @param Container $container
     * @return array
     */
    public function getFromContainer(Container $container): array
    {
        $result = [
            'reference' => substr($container->getSku(), 0, 64),
            'name' => $container->getName(),
            'quantity' => $container->getQty(),
            'type' => $this->getType($container),
            'discount_rate' => 0,
            'tax_rate' => 0,
            'total_tax_amount' => 0,
            'total_discount_amount' => $this->dataConverter->toApiFloat($container->getDiscountAmount())
        ];

        $productUrl = $container->getProductUrl();
        if ($productUrl !== '') {
            $result['product_url'] = $productUrl;
        }

        $imageUrl = $container->getImageUrl();
        if ($imageUrl !== '') {
            $result['image_url'] = $imageUrl;
        }

        return $result;
    }

    /**
     * Getting back the type
     *
     * @param Container $container
     * @return string
     */
    private function getType(Container $container): string
    {
        if ($container->isProductTypeVirtualOrDownloadable()) {
            return ItemGenerator::ITEM_TYPE_VIRTUAL;
        }

        return ItemGenerator::ITEM_TYPE_PHYSICAL;
    }
}
