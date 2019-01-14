<?php
declare(strict_types=1);

namespace Deity\Catalog\Plugin\Model;

use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Deity\MagentoApi\Helper\Product as DeityProductHelper;
use Magento\Catalog\Model\Product as MagentoProduct;

/**
 * Class Product
 * @package Deity\Catalog\Plugin\Model
 */
class Product
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @param ProductImageProviderInterface $imageProvider
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ProductImageProviderInterface $imageProvider,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->imageProvider = $imageProvider;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add resized image information to the product's extension attributes.
     *
     * @param MagentoProduct $product
     * @return MagentoProduct
     */
    public function afterLoad(MagentoProduct $product)
    {

        $productExtension = $product->getExtensionAttributes();

        $mainImage = $this->imageProvider->getProductImageTypeUrl($product, 'product_page_image_large');
        $productExtension->setData('thumbnail_url', $mainImage);
        $thumbUrl = $this->imageProvider->getProductImageTypeUrl($product, 'product_list_thumbnail');
        $productExtension->setData('thumbnail_resized_url', $thumbUrl);

        $product->setExtensionAttributes($productExtension);
        return $product;
    }
}
