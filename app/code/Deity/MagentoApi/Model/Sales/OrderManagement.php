<?php
declare(strict_types=1);

namespace Deity\MagentoApi\Model\Sales;

use Deity\MagentoApi\Api\Sales\OrderManagementInterface;

/**
 * Class OrderManagement
 * @package Deity\MagentoApi\Model\Sales
 */
class OrderManagement implements OrderManagementInterface
{

    /**
     * Get order_id from paypal hash
     *
     * @param string $paypalHash
     * @return int
     */
    public function getOrderIdFromHash(string $paypalHash): int
    {
        // TODO: Implement getOrderIdFromHash() method.
    }
}
