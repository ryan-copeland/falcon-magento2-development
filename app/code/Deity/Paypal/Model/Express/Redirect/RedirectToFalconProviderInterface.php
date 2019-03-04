<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

use Magento\Quote\Api\Data\CartInterface;

/**
 * Interface RedirectToFalconProviderInterface
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
interface RedirectToFalconProviderInterface
{
    /**
     * @param CartInterface $quote
     * @return string
     */
    public function getSuccessUrl(CartInterface $quote): string;

    /**
     * @param CartInterface $quote
     * @return string
     */
    public function getCancelUrl(CartInterface $quote): string;

    /**
     * @param CartInterface $quote
     * @return string
     */
    public function getFailureUrl(CartInterface $quote): string;

    /**
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnSuccessUrl(CartInterface $quote): string;

    /**
     * @param CartInterface $quote
     * @return string
     */
    public function getPaypalReturnCancelUrl(CartInterface $quote): string;
}
