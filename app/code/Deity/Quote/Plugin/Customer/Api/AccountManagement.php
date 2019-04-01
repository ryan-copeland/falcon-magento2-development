<?php
declare(strict_types=1);

namespace Deity\Quote\Plugin\Customer\Api;

use Deity\QuoteApi\Model\CartMergeManagementInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AccountManagement
 *
 * @package Deity\Quote\Plugin\Customer\Api
 */
class AccountManagement
{

    /**
     * @var CartMergeManagementInterface
     */
    private $cartMergeManagement;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AccountManagement constructor.
     *
     * @param CartMergeManagementInterface $cartMergeManagement
     * @param LoggerInterface $logger
     */
    public function __construct(
        CartMergeManagementInterface $cartMergeManagement,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->cartMergeManagement = $cartMergeManagement;
    }

    /**
     * Plugin around creaateAccountWithPasswordHash function
     *
     * @param AccountManagementInterface $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @param string $hash
     * @param string $redirectUrl
     * @return CustomerInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundCreateAccountWithPasswordHash(
        AccountManagementInterface $subject,
        callable $proceed,
        CustomerInterface $customer,
        $hash,
        $redirectUrl
    ) {
        $extensionAttributes = $customer->getExtensionAttributes();
        $quoteId = $extensionAttributes ? $extensionAttributes->getGuestQuoteId() : null;

        /** @var CustomerInterface $result */
        $customer = $proceed($customer, $hash, $redirectUrl);
        if ($quoteId) {
            try {
                $this->cartMergeManagement->convertGuestCart($quoteId, $customer);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }

        return $customer;
    }
}
