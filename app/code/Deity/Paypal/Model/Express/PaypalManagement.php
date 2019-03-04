<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\Paypal\Model\Express\Redirect\RedirectToFalconProviderInterface;
use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;
use Deity\PaypalApi\Api\Data\Express\PaypalDataInterfaceFactory;
use Magento\Checkout\Helper\Data;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Paypal\Model\Config;
use Magento\Paypal\Model\ConfigFactory;
use Magento\Paypal\Model\Express\Checkout;
use Magento\Paypal\Model\Express\Checkout\Factory as PaypalCheckoutFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;

/**
 * Class PaypalManagement
 *
 * @package Deity\Paypal\Model\Express
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PaypalManagement implements PaypalManagementInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Url
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Quote Mask Factory
     * @var QuoteIdMaskFactory
     */
    private $quoteMaskFactory;

    /**
     * Cart Repository
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * Quote
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * Internal cache of checkout models
     *
     * @var array
     */
    private $checkoutTypes = [];

    /**
     * @var Checkout\Factory
     */
    private $checkoutFactory;

    /**
     * @var PaypalDataInterfaceFactory
     */
    private $paypalDataFactory;

    /**
     * @var Data
     */
    private $checkoutHelper;

    /**
     * @var RedirectToFalconProviderInterface
     */
    private $urlProvider;

    /**
     * Payment constructor.
     * @param UrlInterface $urlBuilder
     * @param Data $checkoutHelper
     * @param QuoteIdMaskFactory $quoteMaskFactory
     * @param CartRepositoryInterface $cartRepository
     * @param Checkout\Factory $checkoutFactory
     * @param PaypalDataInterfaceFactory $paypalDataFactory
     * @param RedirectToFalconProviderInterface $urlProvider
     * @param ConfigFactory $configFactory
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Data $checkoutHelper,
        QuoteIdMaskFactory $quoteMaskFactory,
        CartRepositoryInterface $cartRepository,
        PaypalCheckoutFactory $checkoutFactory,
        PaypalDataInterfaceFactory $paypalDataFactory,
        RedirectToFalconProviderInterface $urlProvider,
        ConfigFactory $configFactory
    ) {
        $this->urlProvider = $urlProvider;
        $this->paypalDataFactory = $paypalDataFactory;
        $this->urlBuilder = $urlBuilder;
        $this->checkoutHelper = $checkoutHelper;
        $this->quoteMaskFactory = $quoteMaskFactory;
        $this->cartRepository = $cartRepository;
        $this->checkoutFactory = $checkoutFactory;
        $this->config = $configFactory->create(
            ['params' => [Config::METHOD_WPP_EXPRESS]]
        );
    }

    /**
     * Return Express checkout
     *
     * @param string $cartId
     * @return Checkout
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getExpressCheckout(string $cartId): Checkout
    {
        $this->initQuote($cartId);
        return $this->getCheckout();
    }


    /**
     * Get Express checkout instance
     *
     * @return Checkout
     * @throws LocalizedException
     */
    private function getCheckout(): Checkout
    {
        if (!isset($this->checkoutTypes[Checkout::class])) {
            if (!$this->quote->hasItems() || $this->quote->getHasError()) {
                throw new LocalizedException(__('We can\'t initialize Express Checkout.'));
            }

            $parameters = [
                'params' => [
                    'quote' => $this->quote,
                    'config' => $this->config,
                ],
            ];
            $this->checkoutTypes[Checkout::class] = $this->checkoutFactory
                ->create(Checkout::class, $parameters);
        }
        return $this->checkoutTypes[Checkout::class];
    }
    /**
     * Create paypal token
     *
     * @param string $cartId
     * @return string
     * @throws LocalizedException
     */
    private function createToken(string $cartId): string
    {
        $hasButton = false; // @todo needs to be parametrized. Parameter: button=[1 / 0]
        /** @var Data $checkoutHelper */
        $quoteCheckoutMethod = $this->quote->getCheckoutMethod();
        if ($this->quote->getIsMultiShipping()) {
            $this->quote->setIsMultiShipping(0);
            $this->quote->removeAllAddresses();
        }
        if ((!$quoteCheckoutMethod || $quoteCheckoutMethod !== Onepage::METHOD_REGISTER)
            && !$this->checkoutHelper->isAllowedGuestCheckout($this->quote, $this->quote->getStoreId())
        ) {
            throw new LocalizedException(__('To check out, please sign in with your email address.'));
        }
        // billing agreement
        $this->getCheckout()->setIsBillingAgreementRequested(false);
        // Bill Me Later
        $this->getCheckout()->setIsBml(false); // @todo needs to be parametrized. Parameter: bml=[1 / 0]
        // giropay
        $this->getCheckout()->prepareGiropayUrls(
            $this->urlBuilder->getUrl('checkout/onepage/success'),
            $this->urlBuilder->getUrl('paypal/express/cancel', ['cart_id' => $cartId]),
            $this->urlBuilder->getUrl('checkout/onepage/success')
        );

        $this->quote->getCustomerIsGuest();

        return $this->getCheckout()->start(
            $this->urlProvider->getPaypalReturnSuccessUrl($this->quote),
            $this->urlProvider->getPaypalReturnCancelUrl($this->quote),
            $hasButton
        );
    }

    /**
     * Create paypal token data
     *
     * @param string $cartId
     * @return PaypalDataInterface
     * @throws LocalizedException
     */
    public function createPaypalData(string $cartId): PaypalDataInterface
    {
        $this->initQuote($cartId);
        $token = $this->createToken($cartId);
        $url = $this->getCheckout()->getRedirectUrl();

        $paymentData = $this->paypalDataFactory->create(
            [
                PaypalDataInterface::TOKEN => $token,
                PaypalDataInterface::URL => $url,
            ]
        );

        return $paymentData;
    }

    /**
     * Init quote from given Id
     *
     * @param string $cartId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function initQuote(string $cartId): void
    {
        $this->quote = $this->cartRepository->getActive($cartId);
    }
}
