<?php
declare(strict_types=1);

namespace Deity\Store\Test\Api;

use Magento\Integration\Model\AdminTokenServiceTest;
use Magento\Store\Api\StoreConfigManagerTest as MagentoStoreConfigManagerTest;
use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class StoreConfigManagerTest
 * @package Deity\Store\Test\Api
 */
class StoreConfigManagerTest extends WebapiAbstract
{
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

    /**
     * @magentoApiDataFixture Magento/Webapi/_files/webapi_user.php
     */
    public function testGetStoreConfigs()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => MagentoStoreConfigManagerTest::RESOURCE_PATH,
                'httpMethod'  => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
                'token' => $this->getAccessToken()
            ]
        ];

        $storeViews = $this->_webApiCall($serviceInfo, []);
        $this->assertTrue(isset($storeViews[0]['extension_attributes']), 'Store view should have extension attributes');
        $expectedExtensionKeys = [
            'optional_post_codes',
            'min_password_length',
            'min_password_char_class',
        ];
        $this->assertEquals(
            $expectedExtensionKeys,
            array_intersect(array_keys($storeViews[0]['extension_attributes']), $expectedExtensionKeys),
            'Store view should contain new extension attributes'
        );
    }
}
