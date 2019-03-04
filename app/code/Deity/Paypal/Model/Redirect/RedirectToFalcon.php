<?php
declare(strict_types=1);

namespace Deity\Paypal\Model\Redirect;

use Magento\Quote\Model\Quote;

/**
 * Class RedirectToFalcon
 *
 * @package Deity\Paypal\Model\Redirect
 */
class RedirectToFalcon implements RedirectToFalconProviderInterface
{

    const SUCCESS_URL_DATA_KEY = 'redirect_success';

    const CANCEL_URL_DATA_KEY = 'redirect_cancel';

    const FAILURE_URL_DATA_KEY = 'redirect_failure';

    /**
     * @param Quote $quote
     * @return string
     */
    public function getSuccessUrl(Quote $quote = null): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::SUCCESS_URL_DATA_KEY);
    }

    /**
     * @param Quote|null $quote
     * @return string
     */
    public function getCancelUrl(Quote $quote = null): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::CANCEL_URL_DATA_KEY);
    }

    /**
     * @param Quote|null $quote
     * @return string
     */
    public function getFailureUrl(Quote $quote = null): string
    {
        return (string)$quote->getPayment()->getAdditionalInformation(self::FAILURE_URL_DATA_KEY);
    }
}
