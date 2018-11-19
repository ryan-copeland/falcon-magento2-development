<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\CategoryProductListInterface;
use Deity\CatalogApi\Api\ProductConvertInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterfaceFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Registry;

/**
 * Class CategoryProductList
 * @package Deity\Catalog\Model
 */
class CategoryProductList implements CategoryProductListInterface
{

    /**
     * @var ProductSearchResultsInterfaceFactory
     */
    private $productSearchResultFactory;
    
    /**
     * @var ProductConvertInterface
     */
    private $productConverter;

    /**
     * Catalog layer
     *
     * @var \Magento\Catalog\Model\Layer
     */
    private $catalogLayer;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Stock
     */
    private $stockHelper;

    /**
     * CategoryProductList constructor.
     * @param ProductSearchResultsInterfaceFactory $productSearchResultFactory
     * @param ProductConvertInterface $convert
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Registry $registry
     * @param Stock $stockHelper
     * @param Resolver $layerResolver
     */
    public function __construct(
        ProductSearchResultsInterfaceFactory $productSearchResultFactory,
        ProductConvertInterface $convert,
        CategoryRepositoryInterface $categoryRepository,
        Registry $registry,
        Stock $stockHelper,
        Resolver $layerResolver
    ) {
        $this->stockHelper = $stockHelper;
        $this->productConverter = $convert;
        $this->productSearchResultFactory = $productSearchResultFactory;
        $this->catalogLayer = $layerResolver->get();
        $this->categoryRepository = $categoryRepository;
        $this->registry = $registry;
    }

    /**
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface {

        $currentCategory = $this->categoryRepository->get($categoryId);
        $this->registry->register('current_category', $currentCategory);
        $this->catalogLayer->setCurrentCategory($currentCategory);

        $responseProducts = [];
        foreach ($this->getProductCollection() as $productObject) {
            /** @var $productObject Product */
            $responseProducts[] = $this->productConverter->convert($productObject);
        }
        /** @var ProductSearchResultsInterface $productSearchResult */
        $productSearchResult = $this->productSearchResultFactory->create();

        $productSearchResult->setItems($responseProducts);

        $productSearchResult->setTotalCount($this->getProductCollection()->getSize());

        return $productSearchResult;
    }

    /**
     * @return Collection
     */
    private function getProductCollection(): Collection
    {
        $collection = $this->catalogLayer->getProductCollection();
        $this->stockHelper->addIsInStockFilterToCollection($collection);
        return $collection;
    }
}
