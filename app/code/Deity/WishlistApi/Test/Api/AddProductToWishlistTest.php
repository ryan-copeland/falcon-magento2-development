<?php
declare(strict_types=1);

namespace Deity\WishlistApi\Test\Api;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Registry;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Integration\Model\Oauth\Token as TokenModel;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class AddProductToWishlistTest
 * @package Deity\WishlistApi\Test\Api
 */
class AddProductToWishlistTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/falcon/wishlists/mine/add-product';

    const RESOURCE_PATH_CUSTOMER_TOKEN = '/V1/integration/customer/token';

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var CustomerRegistry
     */
    private $customerRegistry;

    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $objectManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

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

        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->customerRegistry = $this->objectManager->create(CustomerRegistry::class);

        $this->customerRepository = $this->objectManager->create(
            CustomerRepositoryInterface::class,
            ['customerRegistry' => $this->customerRegistry]
        );

        $this->productRepository = $this->objectManager->create(ProductRepositoryInterface::class);
    }

    /**
     * Ensure that fixture customer is deleted.
     */
    public function tearDown()
    {
        if (isset($this->customerData[CustomerInterface::ID])) {
            /** @var Registry $registry */
            $registry = Bootstrap::getObjectManager()->get(Registry::class);
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
     * @magentoApiDataFixture Magento/Catalog/_files/product_with_image.php
     * @magentoApiDataFixture Magento/Customer/_files/customer.php
     */
    public function testAddProductToWishlist()
    {
        $this->getCustomerAccessToken('customer@example.com', 'password');

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
                'token' => $this->token,
            ],
        ];

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productRepository->get('simple');

        $requestData = [
            'add_to_wishlist' => [
                'qty' => 2,
                'product_id' => $product->getEntityId()
            ]
        ];

        $response = $this->_webApiCall($serviceInfo, $requestData);
        $this->assertEquals(true, $response);
    }

    /**
     * Sets the test's access token for a particular username and password.
     *
     * @param string $username
     * @param string $password
     */
    protected function getCustomerAccessToken($username, $password)
    {
        // get customer ID token
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH_CUSTOMER_TOKEN,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
        ];
        $requestData = ['username' => $username, 'password' => $password];
        $this->token = $this->_webApiCall($serviceInfo, $requestData);
    }
}
