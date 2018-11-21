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
    public function testGetListRequestNoParameters()
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

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    public function testGetListNoParametersWithFilters()
    {
        $categoryId = 4;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertEmpty($response['filters'], "Filter data is not expected");

        $categoryId = 3;
        $serviceInfo = [
            'rest' => [
                'resourcePath' => str_replace(':categoryId', $categoryId, self::RESOURCE_PATH),
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $response = $this->_webApiCall($serviceInfo);

        $this->assertNotEmpty($response['filters'], "Filter data is expected");

        $filterableOption = array_filter(
            $response['filters'],
            function ($item) {
                if ($item['code'] == 'filterable_attribute') {
                    return true;
                }
                return false;
            }
        );
        $filterableOption = array_pop($filterableOption);

        $this->assertEquals(2, count($filterableOption['options']), 'Two filter option is expected');
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/CatalogApi/Test/_files/categories_with_filters.php
     */
    /*
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
    */
}
