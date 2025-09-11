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

/**
 * @internal
 */
class ExclusiveTaxCalculator
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
     * @param BaseResult $baseResult
     * @param DataConverter $dataConverter
     * @codeCoverageIgnore
     */
    public function __construct(BaseResult $baseResult, DataConverter $dataConverter)
    {
        $this->baseResult = $baseResult;
        $this->dataConverter = $dataConverter;
    }

    /**
     * Calculating the values exclusive tax
     *
     * @param Container $container
     * @return array
     */
    public function getOrderLineItem(Container $container): array
    {
        $itemResult = $this->baseResult->getFromContainer($container);

        $itemResult['unit_price'] =
            $this->dataConverter->toApiFloat($container->getRowTotal()) / $itemResult['quantity'];
        $itemResult['total_amount'] =
            $this->dataConverter->toApiFloat($container->getRowTotal() - $container->getDiscountAmount());

        return $itemResult;
    }
}
