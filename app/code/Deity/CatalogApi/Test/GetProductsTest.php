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

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testGetListWithFilters()
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
            'includeSubcategories' => 0,
            'withAttributeFilters' => ['filterable_attribute']
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertNotEmpty($response['filters'], "Filter data is expected");

        $this->assertEquals(1, count($response['filters'][0]['options']), 'One filter option is expected');

        $searchCriteria['includeSubcategories'] = 1;

        $serviceInfo['rest']['resourcePath'] = self::RESOURCE_PATH . '?' . http_build_query($searchCriteria);

        $response = $this->_webApiCall($serviceInfo);

        $this->assertEquals(2, count($response['filters'][0]['options']), 'Two filter option is expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testGetListFilterReturnFields()
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
            'includeSubcategories' => 0,
            'withAttributeFilters' => ['filterable_attribute']
        ];

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '?' . http_build_query($searchCriteria),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertNotNull($response['filters'][0], "Filter data is expected");
        $filterData = $response['filters'][0];

        $this->assertEquals('Filterable Attribute', $filterData['label'], "Filter label should be set");
        $this->assertEquals('filterable_attribute', $filterData['code'], "Filter code should be set");
        $this->assertEquals('int', $filterData['type'], "Filter backend type should be set");

        $this->assertNotNull($response['filters'][0]['options'], "Filter data is expected");
        $optionsData = $response['filters'][0]['options'][0];

        $this->assertArrayHasKey('label', $optionsData, "Attribute option label should be provided");
        $this->assertArrayHasKey('value', $optionsData, "Attribute option value should be provided");
    }
}
