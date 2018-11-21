<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class CategoryProductListTest
 * @package Deity\CatalogApi\Test\Api
 */
class CategoryProductListTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/categories/:categoryId/products';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetListRequestWithNoExtraParameters()
    {
        $childCategoryId = 4;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $childCategoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product is expected');

        $parentCategoryId = 3;

        $serviceInfo['rest']['resourcePath'] = str_replace(':categoryId', $parentCategoryId, self::RESOURCE_PATH);

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products are expected');
    }
}
