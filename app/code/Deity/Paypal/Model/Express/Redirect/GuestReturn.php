<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Redirect;

use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\RedirectPaypalExpressInterface;
use Deity\PaypalApi\Api\Data\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\GuestReturnInterface;
use Deity\PaypalApi\Api\GuestPaypalReturnInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Quote\Model\Quote;

/**
 * Class GuestReturn
 *
 * @package Deity\Paypal\Model\Redirect
 */
class GuestReturn implements GuestReturnInterface
{

    /**
     * @var RedirectDataInterfaceFactory;
     */
    private $redirectDataFactory;

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
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Url
     */
    private $urlBuilder;
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var RedirectToFalconProviderInterface
     */
    private $urlProvider;

    /**
     * @var Quote
     */
    private $quote;

    /**
     * @var
     */
    private $checkout;


    /**
     * Initialize Quote based on masked Id
     * @param $cartId
     * @return Quote
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initQuote($cartId)
    {
        if (!ctype_digit($cartId)) {
            $quoteMask = $this->quoteMaskFactory->create()->load($cartId, 'masked_id');
            $quoteId = $quoteMask->getQuoteId();
        } else {
            $quoteId = $cartId;
        }
        $this->setQuote($this->cartRepository->getActive($quoteId));
        return $this->_quote;
    }

    /**
     * Instantiate quote and checkout
     *
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initCheckout()
    {
        $quote = $this->_getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->getResponse()->setStatusHeader(403, '1.1', 'Forbidden');
            throw new \Magento\Framework\Exception\LocalizedException(__('We can\'t initialize Express Checkout.'));
        }
        if (!isset($this->_checkoutTypes[$this->_checkoutType])) {
            $parameters = [
                'params' => [
                    'quote' => $quote,
                    'config' => $this->_config,
                ],
            ];
            $this->_checkoutTypes[$this->_checkoutType] = $this->_checkoutFactory
                ->create($this->_checkoutType, $parameters);
        }
        $this->_checkout = $this->_checkoutTypes[$this->_checkoutType];
    }

    /**
     * Process return from paypal gateway
     *
     * @param string $cartId
     * @param string $token
     * @param string $payerId
     * @return RedirectPaypalExpressInterface
     */
    public function processReturn(string $cartId, string $token, string $payerId): RedirectDataInterface
    {
        $redirectUrlFailure = '';
        $message = __('');
        $orderId = '';
        try {
            $quote = $this->initQuote($cartId);
            $this->_initCheckout();
            $this->_checkout->returnFromPaypal($token);
            if ($this->_checkout->canSkipOrderReviewStep()) {
                $this->placeOrder($token, $cartId, $payerId);
                // redirect if PayPal specified some URL (for example, to Giropay bank)
                $url = $this->_checkout->getRedirectUrl();
                if ($url) {
                    $redirectUrl = $url;
                } else {
                    $redirectUrl = $this->urlProvider->getSuccessUrl($quote);
                    $message = __('Your Order got a number: #%1', $this->_checkout->getOrder()->getIncrementId());
                    $orderId = $this->_checkout->getOrder()->getIncrementId();
                }
            } else {
                throw new LocalizedException(__('Review page is not supported!'));
            }
        } catch (LocalizedException $e) {
            $this->logger->critical('PayPal Return Action: ' . $e->getMessage());
            $redirectUrl = $redirectUrlFailure;
            $message = __('Reason: %1', $e->getMessage());
        } catch (Exception $e) {
            $this->logger->critical('PayPal Return Action: ' . $e->getMessage());
            $message = __('Reason: %1', $e->getMessage());
            $redirectUrl = $redirectUrlFailure;
        }
        $urlParams = [
            ActionInterface::PARAM_NAME_URL_ENCODED => base64_encode((string)$message),
            'order_id' => $orderId,
            'result_redirect' => 1
        ];
        $urlParams = http_build_query($urlParams);
        if (strpos($redirectUrl, 'http') !== false) {
            $sep = (strpos($redirectUrl, '?') === false) ? '?' : '&';
            $redirectUrl = $redirectUrl . $sep . $urlParams;
        } else {
            $redirectUrl = $this->urlBuilder->getBaseUrl() . trim($redirectUrl, ' /') . '?' . $urlParams;
        }

        return $this->redirectDataFactory->create([RedirectPaypalExpressInterface::REDIRECT_FIELD => $redirectUrl]);
    }

    /**
     * Place Order
     * @param $token
     * @param $cartId
     * @param $payerId
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function placeOrder($token, $cartId, $payerId)
    {
        // Fix: reinitialize quote and checkout.
        $this->resetCheckoutTypes();
        $this->initQuote($cartId);
        $this->_initCheckout();
        $this->_checkout->place($token);
        // prepare session to success or cancellation page
        $this->_getCheckoutSession()->clearHelperData();
        // "last successful quote"
        $quoteId = $this->_getQuote()->getId();
        $this->_getCheckoutSession()->setLastQuoteId($quoteId)->setLastSuccessQuoteId($quoteId);
        // an order may be created
        $order = $this->_checkout->getOrder();
        if ($order) {
            $this->_getCheckoutSession()->setLastOrderId($order->getId())
                ->setLastRealOrderId($order->getIncrementId())
                ->setLastOrderStatus($order->getStatus());
        }
        $this->_eventManager->dispatch(
            'paypal_express_place_order_success', [
                'order' => $order,
                'quote' => $this->_getQuote()
            ]
        );
    }
    /**
     * Reset checkout types
     */
    protected function resetCheckoutTypes()
    {
        unset($this->_checkoutTypes);
        $this->_checkoutTypes = [];
    }
}
