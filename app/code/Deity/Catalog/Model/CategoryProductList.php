<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\CategoryProductListInterface;
use Deity\CatalogApi\Api\Data\ProductInterfaceFactory;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterface;
use Deity\CatalogApi\Api\Data\ProductSearchResultsInterfaceFactory;

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
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * CategoryProductList constructor.
     * @param ProductSearchResultsInterfaceFactory $productSearchResultFactory
     * @param ProductInterfaceFactory $productFactory
     */
    public function __construct(ProductSearchResultsInterfaceFactory $productSearchResultFactory, ProductInterfaceFactory $productFactory)
    {
        $this->productSearchResultFactory = $productSearchResultFactory;
        $this->productFactory = $productFactory;
    }


    /**
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): ProductSearchResultsInterface {

        /** @var ProductSearchResultsInterface $productSearchResult */
        $productSearchResult = $this->productSearchResultFactory->create();
        $product = $this->productFactory->create();
        $productSearchResult->setItems([$product]);
        return $productSearchResult;
    }
}
