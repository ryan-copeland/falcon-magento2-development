<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Deity\Paypal\Model\Express\PaypalManagementInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;
use Deity\PaypalApi\Api\Data\Express\RedirectDataInterfaceFactory;
use Deity\PaypalApi\Api\Express\CustomerReturnInterface;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Url;
use Magento\Paypal\Model\Express\Checkout;
use Magento\Quote\Api\CartRepositoryInterface;

/**
 * Class CustomerReturn
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
class CustomerReturn implements CustomerReturnInterface
{

    /**
     * @var PaypalManagementInterface
     */
    private $paypalManagement;

    /**
     * @var RedirectToFalconProviderInterface
     */
    private $redirectToFalconProvider;

    /**
     * @var RedirectDataInterfaceFactory
     */
    private $redirectDataFactory;

    /**
     * Cart Repository
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * CustomerReturn constructor.
     * @param PaypalManagementInterface $paypalManagement
     * @param CartRepositoryInterface $cartRepository
     * @param RedirectToFalconProviderInterface $redirectToFalconProvider
     * @param RedirectDataInterfaceFactory $redirectDataFactory
     * @param Url $urlBuilder
     */
    public function __construct(
        PaypalManagementInterface $paypalManagement,
        CartRepositoryInterface $cartRepository,
        RedirectToFalconProviderInterface $redirectToFalconProvider,
        RedirectDataInterfaceFactory $redirectDataFactory,
        Url $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->cartRepository = $cartRepository;
        $this->paypalManagement = $paypalManagement;
        $this->redirectToFalconProvider = $redirectToFalconProvider;
        $this->redirectDataFactory = $redirectDataFactory;
    }


    /**
     * Process return from paypal gateway
     *
     * @param string $cartId
     * @param string $token
     * @param string $payerId
     * @return \Deity\PaypalApi\Api\Data\Express\RedirectDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function processReturn(string $cartId, string $token, string $payerId): RedirectDataInterface
    {
        $quote = $this->cartRepository->getActive($cartId);
        /** @var Checkout $checkout */
        $checkout = $this->paypalManagement->getExpressCheckout($cartId);
        $checkout->returnFromPaypal($token);
        $flag = $checkout->canSkipOrderReviewStep();

        $checkout->place($token);

        $successUrl = $this->redirectToFalconProvider->getSuccessUrl($quote);
        $message = __('Your Order got a number: #%1', $checkout->getOrder()->getIncrementId());
        $orderId = $checkout->getOrder()->getIncrementId();

        $urlParams = [
            ActionInterface::PARAM_NAME_URL_ENCODED => base64_encode((string)$message),
            'order_id' => $orderId,
            'result_redirect' => 1
        ];
        $urlParams = http_build_query($urlParams);
        if (strpos($successUrl, 'http') !== false) {
            $sep = (strpos($successUrl, '?') === false) ? '?' : '&';
            $successUrl = $successUrl . $sep . $urlParams;
        } else {
            $successUrl = $this->urlBuilder->getBaseUrl() . trim($successUrl, ' /') . '?' . $urlParams;
        }

        return $this->redirectDataFactory->create([RedirectDataInterface::REDIRECT_FIELD => $successUrl]);
    }
}
