<?php
declare(strict_types=1);

namespace Deity\QuoteApi\Test\Api;

use Magento\Framework\App\Config;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GuestCartManagementTest
 *
 * @package Deity\QuoteApi\Test\Api
 */
class GuestCartManagementTest extends WebapiAbstract
{
    /**
     * Service constants
     */
    const RESOURCE_PATH = '/V1/guest-carts/:cartId/deity-order';

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     *  setup before every test run. Update app config
     */
    protected function setUp()
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $appConfig = $this->objectManager->get(Config::class);
        $appConfig->clean();
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/QuoteApi/Test/_files/guest_quote_with_check_payment.php
     */
    public function testPlaceOrderWithoutExtraPaymentInfo()
    {
        $this->_markTestAsRestOnly();
        $testQuoteId = 1;

        /** @var QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedIdConverter */
        $quoteIdToMaskedIdConverter = $this->objectManager->create(\Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface::class);
        $maskedId = $quoteIdToMaskedIdConverter->execute($testQuoteId);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':cartId', $maskedId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_PUT
            ],
        ];

        $orderResponseObject = $this->_webApiCall($serviceInfo, []);

        $this->assertArrayHasKey('order_id', $orderResponseObject, 'response expected to have order_id field');
        $this->assertArrayHasKey(
            'order_real_id',
            $orderResponseObject,
            'response expected to have real_order_id field'
        );
        $orderId = $orderResponseObject['order_id'];
        $orderRealId = $orderResponseObject['order_real_id'];

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create(\Magento\Sales\Model\Order::class)->load($orderId);
        $items = $order->getAllItems();
        $this->assertCount(1, $items, 'order should have exactly one item');
        $this->assertEquals($orderRealId, $order->getIncrementId(), 'Order increment_id should match');
        $this->assertEquals('Simple Product', $items[0]->getName(), 'product name should match');
    }
}
