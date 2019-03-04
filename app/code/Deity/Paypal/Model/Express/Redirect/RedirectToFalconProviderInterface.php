<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Express\Redirect;

/**
 * Interface RedirectToFalconProviderInterface
 *
 * @package Deity\Paypal\Model\Express\Redirect
 */
interface RedirectToFalconProviderInterface
{
    /**
     * @return string
     */
    public function getSuccessUrl(): string;

    /**
     * @return string
     */
    public function getCancelUrl(): string;

    /**
     * @return string
     */
    public function getFailureUrl(): string;
}
