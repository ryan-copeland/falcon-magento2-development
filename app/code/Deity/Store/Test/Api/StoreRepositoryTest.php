<?php
declare(strict_types=1);

namespace Deity\Store\Test\Api;

use Magento\Integration\Model\AdminTokenServiceTest;
use Magento\Store\Api\StoreRepositoryTest as MagentoStoreRepositoryTest;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class StoreRepositoryTest
 * @package Deity\Store\Test\Api
 */
class StoreRepositoryTest extends WebapiAbstract
{
    /**
     * @magentoApiDataFixture Magento/Webapi/_files/webapi_user.php
     */
    public function testGetList()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => MagentoStoreRepositoryTest::RESOURCE_PATH,
                'httpMethod'  => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
                'token' => $this->getAccessToken()
            ]
        ];

        $storeViews = $this->_webApiCall($serviceInfo, []);
        $this->assertTrue(isset($storeViews[0]['extension_attributes']), 'Store view should have extension attributes');
        $this->assertTrue(isset($storeViews[0]['extension_attributes']['is_active']), 'Store view should be active');
    }

    /**
     * @return string
     */
    private function getAccessToken(): string
    {
        $adminUserNameFromFixture = 'webapi_user';

        $serviceInfo = [
            'rest' => [
                'resourcePath' => AdminTokenServiceTest::RESOURCE_PATH_ADMIN_TOKEN,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_POST,
            ],
        ];

        $requestData = [
            'username' => $adminUserNameFromFixture,
            'password' => \Magento\TestFramework\Bootstrap::ADMIN_PASSWORD,
        ];
        $accessData = $this->_webApiCall($serviceInfo, $requestData);
        return $accessData['token'];
    }
}
