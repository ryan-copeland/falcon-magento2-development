<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Test\Api;

use Magento\TestFramework\TestCase\WebapiAbstract;

/**
 * Class GetProductsTest
 * @package Deity\CatalogApi\Test\Api
 */
class GetProductsTest extends WebapiAbstract
{

    const RESOURCE_PATH = '/V1/products';

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_children.php
     */
    public function testGetList()
    {
        $searchCriteria = [
            'searchCriteria' => [
                'filter_groups' => [
                    [
                        'filters' => [
                            [
                                'field' => 'category_id',
                                'value' => '3',
                                'condition_type' => 'eq',
                            ],
                        ],
                    ],
                ],
                'current_page' => 1,
                'page_size' => 2
            ],
            'includeSubcategories' => 0
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(1, $response['total_count'], 'One product is expected');

        $searchCriteria['includeSubcategories'] = 1;

        $serviceInfo['rest']['resourcePath'] = self::RESOURCE_PATH . '?' . http_build_query($searchCriteria);

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, $response['total_count'], 'Two products are expected');
    }
}
