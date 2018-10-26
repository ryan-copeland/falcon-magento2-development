<?php
declare(strict_types=1);

namespace Deity\UrlRewriteApi\Test\Api;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\TestFramework\TestCase\WebapiAbstract;

class GetUrlRewriteTest extends WebapiAbstract
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;

    /**
     * Service constants
     */
    const RESOURCE_PATH = '/V1/url';

    /**
     * @param $existingUrl
     * @return array|bool|float|int|string
     */
    public function getUrlRewriteInfo($existingUrl)
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . "?url=" . $existingUrl,
                'httpMethod' => \Magento\Framework\Webapi\Rest\Request::HTTP_METHOD_GET,
            ]
        ];
        $item = $this->_webApiCall($serviceInfo, []);
        return $item;
    }

    /**
     * set up test env
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->fileSystem = $this->objectManager->get(\Magento\Framework\Filesystem::class);
    }

    /**
     * @magentoApiDataFixture ../../../../app/code/Deity/UrlRewriteApi/Test/_files/url_rewrite.php
     */
    public function testExecute()
    {
        $item = $this->getUrlRewriteInfo('page-a');
        $this->assertEquals('CMS_PAGE', $item['entity_type'], "Item was retrieved successfully");

        $item = $this->getUrlRewriteInfo('product-one');
        $this->assertEquals('PRODUCT', $item['entity_type'], "Item was retrieved successfully");
        
        $item = $this->getUrlRewriteInfo('category-one');
        $this->assertEquals('CATEGORY', $item['entity_type'], "Item was retrieved successfully");
    }

    protected function tearDown()
    {
        $rollbackfixturePath = $this->fileSystem->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath('/app/code/Deity/UrlRewriteApi/Test/_files/url_rewrite_rollback.php');
        if (file_exists($rollbackfixturePath)) {
            include $rollbackfixturePath;
        }
    }
}
