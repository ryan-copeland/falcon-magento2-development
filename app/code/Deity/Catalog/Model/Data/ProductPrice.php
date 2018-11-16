<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\ProductPriceInterface;
use Magento\Framework\Api\AbstractSimpleObject;

/**
 * Class ProductPrice
 * @package Deity\Catalog\Model\Data
 */
class ProductPrice extends AbstractSimpleObject implements ProductPriceInterface
{

    /**
     * @return float
     */
    public function getRegularPrice(): float
    {
        return (float)$this->_get(self::REGULAR_PRICE);
    }

    /**
     * @param float $regularPrice
     * @return ProductPriceInterface
     */
    public function setRegularPrice(float $regularPrice): ProductPriceInterface
    {
        $this->setData(self::REGULAR_PRICE, $regularPrice);
        return $this;
    }

    /**
     * @return float
     */
    public function getSpecialPrice(): float
    {
        return (float)$this->_get(self::SPECIAL_PRICE);
    }

    /**
     * @param float $specialPrice
     * @return ProductPriceInterface
     */
    public function setSpecialPrice(float $specialPrice): ProductPriceInterface
    {
        $this->setData(self::SPECIAL_PRICE, $specialPrice);
        return $this;
    }

    /**
     * @return float
     */
    public function getMinTierPrice(): ?float
    {
        return (float)$this->_get(self::MIN_TIER_PRICE);
    }

    /**
     * @param float $minTierPrice
     * @return ProductPriceInterface
     */
    public function setMinTierPrice(?float $minTierPrice): ProductPriceInterface
    {
        $this->setData(self::MIN_TIER_PRICE, $minTierPrice);
        return $this;
    }
}
