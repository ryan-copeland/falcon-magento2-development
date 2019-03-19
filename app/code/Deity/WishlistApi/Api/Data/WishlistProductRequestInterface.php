<?php

namespace Deity\WishlistApi\Api\Data;

interface WishlistProductRequestInterface
{
    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @return mixed | null
     */
    public function getSuperAttribute();
}
