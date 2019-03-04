<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Data\Express;

use Deity\PaypalApi\Api\Data\Express\RedirectDataInterface;

/**
 * Class RedirectData
 *
 * @package Deity\Paypal\Model\Data\Express
 */
class RedirectPaypal implements RedirectDataInterface
{

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * RedirectData constructor.
     * @param string $redirectUrl
     */
    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * Get Redirect URL
     *
     * @return string
     */
    public function getRedirect(): string
    {
        return $this->redirectUrl;
    }
}
