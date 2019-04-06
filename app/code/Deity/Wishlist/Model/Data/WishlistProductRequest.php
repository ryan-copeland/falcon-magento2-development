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
     * @return WishlistProductRequestInterface
     */
    public function setCustomerId(int $customerId)
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
     * @return WishlistProductRequestInterface
     */
    public function setProductId(int $productId)
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
     * @return WishlistProductRequestInterface
     */
    public function setWishlistId(int $wishlistId)
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
     * @return WishlistProductRequestInterface
     */
    public function setQty(int $qty)
    {
        $this->qty = $qty;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuperAttribute()
    {
        return $this->superAttribute;
    }

    /**
     * @param mixed $superAttributes
     * @return WishlistProductRequestInterface
     */
    public function setSuperAttribute($superAttribute)
    {
        $this->superAttribute = $superAttribute;
        return $this;
    }
}
