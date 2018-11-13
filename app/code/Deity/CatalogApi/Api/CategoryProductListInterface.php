<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api;

/**
 * Interface CategoryProductListInterface
 * @package Deity\CatalogApi\Api
 */
interface CategoryProductListInterface
{

    /**
     * @param int $categoryId
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return int
     */
    public function getList(
        int $categoryId,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): int;
}
