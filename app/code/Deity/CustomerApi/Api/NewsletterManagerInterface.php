<?php
namespace Deity\CustomerApi\Api;

interface NewsletterManagerInterface
{
    /**
     * Subscribe customer to newsletter
     *
     * @param int $customerId
     * @return bool
     */
    public function subscribeCustomer($customerId);

    /**
     * Unsubscribe customer from newsletter
     *
     * @param int $customerId
     * @return bool
     */
    public function unsubscribeCustomer($customerId);
}