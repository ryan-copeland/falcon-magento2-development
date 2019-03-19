<?php

namespace Deity\WishlistApi\Api;

use Deity\WishlistApi\Api\Data\WishlistProductRequestInterface;

interface AddProductToWishlistInterface
{
    /**
     * @param WishlistProductRequestInterface $addToWishlist
     * @return mixed
     */
    public function execute(WishlistProductRequestInterface $addToWishlist);
}
