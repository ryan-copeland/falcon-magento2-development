<?php
declare(strict_types=1);

namespace Deity\Wishlist\Model\Data;

use Deity\WishlistApi\Api\Data\WishlistProductRequestInterface;

class WishlistProductRequest implements WishlistProductRequestInterface
{
    /**
     * @var int
     */
    private $customerId;

    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $wishlistId;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var array
     */
    private $superAttribute;

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     * @return WishlistProductRequest
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return WishlistProductRequest
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return int
     */
    public function getWishlistId(): int
    {
        return $this->wishlistId;
    }

    /**
     * @param int $wishlistId
     * @return WishlistProductRequest
     */
    public function setWishlistId($wishlistId)
    {
        $this->wishlistId = $wishlistId;
        return $this;
    }

    /**
     * @return int
     */
    public function getQty(): int
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     * @return WishlistProductRequest
     */
    public function setQty(int $qty)
    {
        $this->qty = $qty;
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getSuperAttribute()
    {
        return $this->superAttribute;
    }

    /**
     * @param array $superAttributes
     * @return WishlistProductRequest
     */
    public function setSuperAttribute(array $superAttribute)
    {
        $this->superAttribute = $superAttribute;
        return $this;
    }
}
