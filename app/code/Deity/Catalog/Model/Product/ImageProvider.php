<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Product;

use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Area;
use Magento\Store\Model\App\Emulation;

/**
 * Class ImageProvider
 * @package Deity\Catalog\Model\Product
 */
class ImageProvider implements ProductImageProviderInterface
{
    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    /**
     * ImageProvider constructor.
     * @param Emulation $appEmulation
     * @param ImageBuilder $imageBuilder
     */
    public function __construct(Emulation $appEmulation, ImageBuilder $imageBuilder)
    {
        $this->appEmulation = $appEmulation;
        $this->imageBuilder = $imageBuilder;
    }

    /**
     * @param Product $product
     * @param string $imageType
     * @return string
     */
    public function getProductImageTypeUrl(Product $product, string $imageType): string
    {
        $this->appEmulation->startEnvironmentEmulation($product->getStoreId(), Area::AREA_FRONTEND, true);
        // TODO: Implement getProductImageTypeUrl() method.
        $imageObject = $this->imageBuilder->setProduct($product)
            ->setImageId($imageType)
            ->create();

        $imageUrl = $imageObject->getImageUrl();

        $this->appEmulation->stopEnvironmentEmulation();

        return $imageUrl;
    }
}
