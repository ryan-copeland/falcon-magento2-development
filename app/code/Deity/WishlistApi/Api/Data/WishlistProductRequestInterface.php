<?php

namespace Deity\WishlistApi\Api\Data;

interface WishlistProductRequestInterface
{
    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @param int $customerId
     * @return WishlistProductRequestInterface
     */
    public function setCustomerId(int $customerId);

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $productId
     * @return WishlistProductRequestInterface
     */
    public function setProductId(int $productId);

    /**
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * @param int $wishlistId
     * @return WishlistProductRequestInterface
     */
    public function setWishlistId(int $wishlistId);

    /**
     * @return int
     */
    public function getQty(): int;

    /**
     * @param int $qty
     * @return WishlistProductRequestInterface
     */
    public function setQty(int $qty);

    /**
     * @return mixed
     */
    public function getSuperAttribute();

    /**
     * @param mixed $superAttributes
     * @return WishlistProductRequestInterface
     */
    public function setSuperAttribute($superAttribute);
}