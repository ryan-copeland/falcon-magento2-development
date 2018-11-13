<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

interface ProductRepositoryInterface extends \Magento\Catalog\Api\ProductRepositoryInterface
{
    /**
     * Get product list
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @param bool $includeSubcategories
     * @param \Deity\CatalogApi\Api\Data\FilterInterface[] $withAttributeFilters
     * @return \Deity\CatalogApi\Api\Data\ProductSearchResultsInterface
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        $includeSubcategories = false,
        $withAttributeFilters = []
    );
}
