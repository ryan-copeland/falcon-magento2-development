<?php

namespace Deity\WishlistApi\Api\Data;

use Deity\Wishlist\Model\Data\WishlistProductRequest;

interface WishlistProductRequestInterface
{
    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @param mixed $customerId
     * @return WishlistProductRequestInterface
     */
    public function setCustomerId($customerId);

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $productId
     * @return WishlistProductRequestInterface
     */
    public function setProductId($productId);

    /**
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * @param int $wishlistId
     * @return WishlistProductRequestInterface
     */
    public function setWishlistId($wishlistId);

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
     * @param array $superAttribute
     * @return WishlistProductRequest
     */
    public function setSuperAttribute(array $superAttribute);

    /**
     * @return mixed | null
     */
    public function getSuperAttribute();
}
