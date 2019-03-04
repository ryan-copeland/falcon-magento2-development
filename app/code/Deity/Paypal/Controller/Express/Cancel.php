<?php
declare(strict_types=1);

namespace Deity\Paypal\Controller\Express;

use Deity\Paypal\Model\Express\Redirect\RedirectToFalconProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Session\Generic;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Framework\UrlInterface;
use Magento\Paypal\Controller\Express\Cancel as CancelAction;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Paypal\Model\Express\Checkout\Factory;
use Psr\Log\LoggerInterface;

/**
 * Class Cancel
 *
 * @package Deity\Paypal\Controller\Express
 */
class Cancel extends CancelAction
{
    /**
     * @var QuoteIdMaskFactory
     */
    private $quoteMaskFactory;
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var RedirectToFalconProviderInterface
     */
    private $urlProvider;

    /**
     * Cancel constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Paypal\Model\Express\Checkout\Factory $checkoutFactory
     * @param \Magento\Framework\Session\Generic $paypalSession
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param QuoteIdMaskFactory $quoteMaskFactory
     * @param CartRepositoryInterface $cartRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     * @param UrlInterface $urlBuilder
     * @param RedirectToFalconProviderInterface $urlProvider
     */
    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession,
        OrderFactory $orderFactory,
        Factory $checkoutFactory,
        Generic $paypalSession,
        UrlHelper $urlHelper,
        Url $customerUrl,
        QuoteIdMaskFactory $quoteMaskFactory,
        CartRepositoryInterface $cartRepository,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger,
        UrlInterface $urlBuilder,
        RedirectToFalconProviderInterface $urlProvider
    ) {
        parent::__construct(
            $context,
            $customerSession,
            $checkoutSession,
            $orderFactory,
            $checkoutFactory,
            $paypalSession,
            $urlHelper,
            $customerUrl
        );
        $this->quoteMaskFactory = $quoteMaskFactory;
        $this->cartRepository = $cartRepository;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->urlBuilder = $urlBuilder;
        $this->urlProvider = $urlProvider;
    }
    /**
     * Quote
     * @return CartInterface|Quote
     */
    protected function _getQuote()
    {
        return $this->quote;
    }
    /**
     * @param CartInterface|Quote $quote
     * @return Cancel
     */
    private function setQuote(CartInterface $quote)
    {
        $this->quote = $quote;
        return $this;
    }
    /**
     * Initialize Quote based on masked Id
     * @param $cartId
     * @return CartInterface|Quote
     * @throws NoSuchEntityException
     */
    private function initQuote($cartId)
    {
        if (!ctype_digit($cartId)) {
            $quoteMask = $this->quoteMaskFactory->create()->load($cartId, 'masked_id');
            $quoteId = $quoteMask->getQuoteId();
        } else {
            $quoteId = $cartId;
        }
        $this->setQuote($this->cartRepository->getActive($quoteId));
        return $this->_getQuote();
    }
    /**
     * Cancel Express Checkout
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $cartId = $this->getRequest()->getParam('cart_id');
        $redirectUrlFailure = '';
        try {
            $quote = $this->initQuote($cartId);
            $redirectUrlFailure = $this->urlProvider->getFailureUrl($quote);
            $redirectUrlCancel = $this->urlProvider->getCancelUrl($quote);
        } catch (LocalizedException $e) {
            $this->logger->critical('PayPal Cancel Action: ' . $e->getMessage());
            $redirectUrlCancel = $redirectUrlFailure;
        }

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
            ->setUrl($this->urlBuilder->getDirectUrl($redirectUrlCancel));

        return $resultRedirect;
    }
}
