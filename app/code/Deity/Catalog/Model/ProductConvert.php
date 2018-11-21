<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\Catalog\Model\Data\ProductPrice;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\Data\ProductInterfaceFactory;
use Deity\CatalogApi\Api\Data\ProductPriceInterfaceFactory;
use Deity\CatalogApi\Api\Data\ProductInterface as DeityProductInterface;
use Deity\CatalogApi\Api\ProductImageProviderInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPriceInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Profiler;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class ProductConvert
 * @package Deity\Catalog\Model
 */
class ProductConvert implements ProductConvertInterface
{

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var ProductInterface
     */
    private $currentProductObject;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var ProductImageProviderInterface
     */
    private $imageProvider;

    /**
     * @var MinimalPriceCalculatorInterface
     */
    private $minimalPriceCalculator;

    /**
     * @var ProductPriceInterfaceFactory
     */
    private $productPriceFactory;

    /**
     * ProductConvert constructor.
     * @param ProductInterfaceFactory $productFactory
     * @param UrlFinderInterface $urlFinder
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param ProductPriceInterfaceFactory $productPriceFactory
     * @param ProductImageProviderInterface $imageProvider
     */
    public function __construct(
        ProductInterfaceFactory $productFactory,
        UrlFinderInterface $urlFinder,
        MinimalPriceCalculatorInterface $minimalPriceCalculator,
        ProductPriceInterfaceFactory $productPriceFactory,
        ProductImageProviderInterface $imageProvider
    ) {
        $this->productPriceFactory = $productPriceFactory;
        $this->minimalPriceCalculator = $minimalPriceCalculator;
        $this->imageProvider = $imageProvider;
        $this->urlFinder = $urlFinder;
        $this->productFactory = $productFactory;
    }

    /**
     * Convert product to array representation
     *
     * @param Product $product
     * @return DeityProductInterface
     * @throws LocalizedException
     */
    public function convert(Product $product): DeityProductInterface
    {
        Profiler::start('__PRODUCT_LISTING_CONVERT__', ['group' => 'Deity']);

        $this->currentProductObject = $product;

        $deityProduct = $this->productFactory->create();
        $deityProduct->setName($product->getName());
        $deityProduct->setSku($product->getSku());
        $deityProduct->setUrlPath($this->getProductUrlPath($product));
        $deityProduct->setIsSalable((int)$product->getIsSalable());
        $priceInfo = $product->getPriceInfo();
        $regularPrice = $priceInfo->getPrice('regular_price');
        $regularPriceValue = $regularPrice->getValue();
        /** @var FinalPriceInterface $priceObject */
        $priceObject = $priceInfo->getPrice('final_price');
        $minPrice = $priceObject->getMinimalPrice();
        $minimalPrice = $this->minimalPriceCalculator->getValue($product);

        /** @var ProductPrice $priceObject */
        $priceObject = $this->productPriceFactory->create();
        $priceObject->setRegularPrice($regularPriceValue)
            ->setSpecialPrice($minPrice->getValue())
            ->setMinTierPrice($minimalPrice);

        $deityProduct->setPrice($priceObject);
        $deityProduct->setImage(
            $this->imageProvider->getProductImageTypeUrl($product, 'deity_category_page_list')
        );

        Profiler::stop('__PRODUCT_LISTING_CONVERT__');
        return $deityProduct;
    }

    /**
     * @param Product $product
     * @return string
     * @throws LocalizedException
     */
    private function getProductUrlPath(Product $product): string
    {
        $filterData = [
            UrlRewrite::ENTITY_ID => $product->getId(),
            UrlRewrite::ENTITY_TYPE => \Magento\CatalogUrlRewrite\Model\ProductUrlRewriteGenerator::ENTITY_TYPE,
            UrlRewrite::STORE_ID => $product->getStoreId(),
        ];

        $filterData[UrlRewrite::METADATA]['category_id'] = $product->getCategoryId();

        $rewrite = $this->urlFinder->findOneByData($filterData);

        if ($rewrite) {
            return  $rewrite->getRequestPath();
        }

        // try to get direct url to magento
        unset($filterData[UrlRewrite::METADATA]);

        $rewrite = $this->urlFinder->findOneByData($filterData);

        if ($rewrite) {
            return  $rewrite->getRequestPath();
        }

        throw new LocalizedException(__('Unable to get seo friendly url for product'));
    }

    /**
     * @return ProductInterface
     */
    public function getCurrentProduct(): ProductInterface
    {
        return $this->currentProductObject;
    }
}
