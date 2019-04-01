<?php
declare(strict_types=1);

namespace Deity\Quote\Model;

use Deity\QuoteApi\Model\CartMergeManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\QuoteIdMask;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Quote\Model\ResourceModel\Quote\QuoteIdMask as QuoteIdMaskResource;

/**
 * Handle merging guest and customer quote when signing up and signing in
 *
 * @package Deity\Quote\Model
 */
class CartMergeManagement implements CartMergeManagementInterface
{
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteIdMaskFactory;

    /**
     * @var QuoteIdMaskResource
     */
    private $quoteIdMaskResource;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * CartMergeManagement constructor.
     *
     * @param QuoteIdMaskFactory $quoteIdMaskFactory
     * @param CartRepositoryInterface $cartRepository
     * @param CustomerRepositoryInterface $customerRepository
     * @param QuoteIdMaskResource $quoteIdMaskResource
     */
    public function __construct(
        QuoteIdMaskFactory $quoteIdMaskFactory,
        CartRepositoryInterface $cartRepository,
        CustomerRepositoryInterface $customerRepository,
        QuoteIdMaskResource $quoteIdMaskResource
    ) {
        $this->quoteIdMaskResource = $quoteIdMaskResource;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->cartRepository = $cartRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Merge guest quote to customer or convert guest quote if customer does not have active one
     *
     * @param string $guestQuoteId
     * @param CartInterface $customerQuote
     * @return bool
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function mergeGuestAndCustomerQuotes(string $guestQuoteId, CartInterface $customerQuote): bool
    {
        $guestCart = $this->getGuestCart($guestQuoteId);
        $customerQuote->merge($guestCart);
        $this->cartRepository->save($customerQuote);

        $this->cartRepository->delete($guestCart);

        return true;
    }

    /**
     * Convert guest quote to customer quote
     *
     * @param string $guestQuoteId
     * @param CustomerInterface $customer
     * @return bool
     * @throws NoSuchEntityException
     */
    public function convertGuestCart(string $guestQuoteId, CustomerInterface $customer): bool
    {
        /** @var CartInterface $guestQuote */
        $guestQuote = $this->getGuestCart($guestQuoteId);
        $guestQuote->assignCustomer($customer);
        $guestQuote->setCheckoutMethod(null);

        $this->cartRepository->save($guestQuote);

        return true;
    }

    /**
     * Get provided guest quote
     *
     * @param string $guestQuoteId
     * @return CartInterface
     * @throws NoSuchEntityException
     */
    private function getGuestCart(string $guestQuoteId): CartInterface
    {
        /** @var QuoteIdMask $quoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create();
        $this->quoteIdMaskResource->load($quoteIdMask, $guestQuoteId, 'masked_id');
        $guestCart = $this->cartRepository->getActive($quoteIdMask->getQuoteId());

        return $guestCart;
    }
}
