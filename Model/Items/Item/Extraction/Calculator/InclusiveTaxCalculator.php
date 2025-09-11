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
use Klarna\Orderlines\Model\Items\Item\Extraction\Container;
use Magento\Tax\Model\Config;

/**
 * @internal
 */
class InclusiveTaxCalculator
{
    /**
     * @var BaseResult
     */
    private BaseResult $baseResult;
    /**
     * @var DataConverter
     */
    private DataConverter $dataConverter;
    /**
     * @var Config
     */
    private Config $taxConfig;

    /**
     * @param BaseResult $baseResult
     * @param DataConverter $dataConverter
     * @param Config $taxConfig
     * @codeCoverageIgnore
     */
    public function __construct(BaseResult $baseResult, DataConverter $dataConverter, Config $taxConfig)
    {
        $this->baseResult = $baseResult;
        $this->dataConverter = $dataConverter;
        $this->taxConfig = $taxConfig;
    }

    /**
     * Calculating the values inclusive tax
     *
     * @param Container $container
     * @return array
     */
    public function getOrderLineItem(Container $container): array
    {
        $itemResult = $this->baseResult->getFromContainer($container);
        $itemResult['tax_rate'] = $this->dataConverter->toApiFloat($container->getTaxPercent());

        $itemResult['total_amount'] = $this->dataConverter->toApiFloat(
            $container->getRowTotalIncludedTax() - $container->getDiscountAmount()
        );
        $itemResult['unit_price'] = $this->dataConverter->toApiFloat(
            $container->getRowTotalIncludedTax() / $itemResult['quantity']
        );

        if ($this->taxConfig->applyTaxAfterDiscount($container->getStore())) {
            $itemResult['total_tax_amount'] = $this->dataConverter->toApiFloat($container->getTaxAmount());
        } else {
            $itemResult['total_tax_amount'] = round(
                $itemResult['total_amount'] -
                (($itemResult['total_amount'] * 10000) / (10000 + $itemResult['tax_rate']))
            );
        }

        return $itemResult;
    }
}
