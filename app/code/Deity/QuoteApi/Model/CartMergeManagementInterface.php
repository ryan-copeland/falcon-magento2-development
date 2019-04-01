<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Interface CartMergeInterface
 *
 * @package Deity\QuoteApi\Model
 */
interface CartMergeManagementInterface
{
    /**
     * Merge guest quote to customer or convert guest quote if customer does not have active one
     *
     * @param string $guestQuoteId
     * @param CartInterface $customerQuote
     * @return bool
     */
    public function mergeGuestAndCustomerQuotes(string $guestQuoteId, CartInterface $customerQuote): bool;

    /**
     * Convert guest quote to customer quote
     *
     * @param string $guestQuoteId
     * @param CustomerInterface $customer
     * @return bool
     */
    public function convertGuestCart(string $guestQuoteId, CustomerInterface $customer): bool;
}
