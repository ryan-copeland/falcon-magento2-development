<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

/**
 * Interface ProductPriceInterface
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductPriceInterface
{
    const REGULAR_PRICE = 'regular_price';

    const SPECIAL_PRICE = 'special_price';

    const MIN_TIER_PRICE = 'min_tier_price';

    /**
     * @return float
     */
    public function getRegularPrice(): float;

    /**
     * @param float $regularPrice
     * @return ProductPriceInterface
     */
    public function setRegularPrice(float $regularPrice): ProductPriceInterface;

    /**
     * @return float
     */
    public function getSpecialPrice(): float;

    /**
     * @param float $specialPrice
     * @return ProductPriceInterface
     */
    public function setSpecialPrice(float $specialPrice): ProductPriceInterface;

    /**
     * @return float
     */
    public function getMinTierPrice(): ?float;

    /**
     * @param float $minTierPrice
     * @return ProductPriceInterface
     */
    public function setMinTierPrice(?float $minTierPrice): ProductPriceInterface;
}
