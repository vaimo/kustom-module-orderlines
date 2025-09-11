<?php
/**
 * Copyright © Klarna Bank AB (publ)
 *
 * For the full copyright and license information, please view the NOTICE
 * and LICENSE files that were distributed with this source code.
 */
declare(strict_types=1);

namespace Klarna\Orderlines\Model\Items\Item;

use Klarna\Orderlines\Api\OrderLineInterface;
use Klarna\Orderlines\Model\Container\DataHolder;
use Klarna\Orderlines\Model\Container\Parameter;
use Klarna\Orderlines\Model\Items\Item\Extraction\Iterator;
use Klarna\Orderlines\Model\Items\Items;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Generate item order line details
 *
 * @api
 */
class Handler implements OrderLineInterface
{
    /**
     * @var Validator
     */
    private Validator $validator;
    /**
     * @var Iterator
     */
    private Iterator $iterator;

    /**
     * @param Validator $validator
     * @param Iterator $iterator
     * @codeCoverageIgnore
     */
    public function __construct(Validator $validator, Iterator $iterator)
    {
        $this->validator = $validator;
        $this->iterator = $iterator;
    }

    /**
     * @inheritDoc
     */
    public function collectPrePurchase(Parameter $parameter, DataHolder $dataHolder, CartInterface $quote): self
    {
        return $this->collect($parameter, $dataHolder, $quote);
    }

    /**
     * @inheritDoc
     */
    public function collectPostPurchase(Parameter $parameter, DataHolder $dataHolder, OrderInterface $order): self
    {
        return $this->collect($parameter, $dataHolder, $order);
    }

    /**
     * Collecting the orderline data
     *
     * @param Parameter $parameter
     * @param DataHolder $dataHolder
     * @param ExtensibleDataInterface $dataObject
     * @return $this
     * @throws \Klarna\Base\Exception
     */
    private function collect(Parameter $parameter, DataHolder $dataHolder, ExtensibleDataInterface $dataObject): self
    {
        if (!$this->validator->isCollectable($dataHolder)) {
            return $this;
        }

        $items = $this->iterator->getCalculatedItems($dataHolder, $dataObject);
        $parameter->setItems($items);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fetch(Parameter $parameter)
    {
        if (!$this->validator->isFetchable($parameter)) {
            return $this;
        }

        foreach ($parameter->getItems() as $item) {
            $parameter->addOrderLine($item);
        }

        return $this;
    }
}
