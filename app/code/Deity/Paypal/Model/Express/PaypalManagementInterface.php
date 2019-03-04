<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express;

use Deity\PaypalApi\Api\Data\Express\PaypalDataInterface;

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
}
