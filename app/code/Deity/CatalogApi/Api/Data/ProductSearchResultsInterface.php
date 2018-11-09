<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface ProductSearchResultsInterface extends \Magento\Catalog\Api\Data\ProductSearchResultsInterface
{
    /**
     * Get filters
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     */
    public function getFilters();

    /**
     * Set filters
     * @param \Deity\CatalogApi\Api\Data\FilterInterface[] $items
     * @return $this
     */
    public function setFilters($items);
}