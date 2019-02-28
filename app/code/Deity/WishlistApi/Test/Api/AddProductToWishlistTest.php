<?php
declare(strict_types=1);

namespace Deity\WishlistApi\Test\Api;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Integration\Model\Oauth\Token as TokenModel;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\Helper\Customer as CustomerHelper;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class AddProductToWishlistTest
 * @package Deity\WishlistApi\Test\Api
 */
class AddProductToWishlistTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/wishlists/mine/add-product';

    const RESOURCE_PATH_CUSTOMER_TOKEN = "/V1/integration/customer/token";

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    /**
     * @var TokenModel
     */
    private $token;

    /**
     * @var CustomerInterface
     */
    private $customerData;

    public function setUp()
    {
        $this->_markTestAsRestOnly();

        $this->customerRegistry = Bootstrap::getObjectManager()->get(
            \Magento\Customer\Model\CustomerRegistry::class
        );

        $this->customerRepository = Bootstrap::getObjectManager()->get(
            \Magento\Customer\Api\CustomerRepositoryInterface::class,
            ['customerRegistry' => $this->customerRegistry]
        );

        $this->customerHelper = new CustomerHelper();
        $this->customerData = $this->customerHelper->createSampleCustomer();

        // get token
        $this->resetTokenForCustomerSampleData();
    }

    /**
     * Ensure that fixture customer and his addresses are deleted.
     */
    public function tearDown()
    {
        if (isset($this->customerData[CustomerInterface::ID])) {
            /** @var \Magento\Framework\Registry $registry */
            $registry = Bootstrap::getObjectManager()->get(\Magento\Framework\Registry::class);
            $registry->unregister('isSecureArea');
            $registry->register('isSecureArea', true);
            $this->customerRepository->deleteById($this->customerData[CustomerInterface::ID]);
            $registry->unregister('isSecureArea');
            $registry->register('isSecureArea', false);
        }
        $this->customerRepository = null;
        parent::tearDown();
    }

    /**
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testAddProductToWishlist()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
                'token' => $this->token,
            ],
        ];

        $requestData = [
            'add_to_wishlist' => [
                'qty' => 2,
                'product_id' => 1
            ]
        ];

        $response = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertEquals(true, $response);
    }
}