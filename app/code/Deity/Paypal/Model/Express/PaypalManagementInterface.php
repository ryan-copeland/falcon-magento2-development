<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;
use Magento\Paypal\Model\Express\Checkout;

/**
 * Class PaypalManagementInterface
 *
 * @package Deity\Paypal\Model\Express
 */
interface PaypalManagementInterface
{

    /**
     * Create paypal token
     *
     * @param string $cartId
     * @return \Deity\PaypalApi\Api\Data\Express\PaypalDataInterface
     */
    public function createPaypalData(string $cartId): PaypalDataInterface;

    /**
     * Get Checkout object by cart ID
     *
     * @param string $cartId
     * @return Checkout
     */
    public function getExpressCheckout(string $cartId): Checkout;
}
