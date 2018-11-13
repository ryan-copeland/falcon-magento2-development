<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\CategoryProductListInterface;

/**
 * Class CategoryProductList
 * @package Deity\Catalog\Model
 */
class CategoryProductList implements CategoryProductListInterface
{

    /**
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return int
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): int {
        return $categoryId;
    }
}
