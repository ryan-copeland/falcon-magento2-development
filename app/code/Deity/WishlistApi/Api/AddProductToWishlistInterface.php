<?php
declare(strict_types=1);

namespace Deity\WishlistApi\Api;

use Deity\WishlistApi\Api\Data\WishlistProductRequestInterface;

interface AddProductToWishlistInterface
{
    /**
     * @param int $customerId
     * @param WishlistProductRequestInterface $addToWishlist
     * @return bool
     */
    public function execute(int $customerId, WishlistProductRequestInterface $addToWishlist): bool;
}
