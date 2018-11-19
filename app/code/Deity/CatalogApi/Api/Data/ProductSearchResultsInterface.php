<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface ProductSearchResultsInterface
{

    const KEY_FILTERS = 'filters';

    const KEY_ITEMS = 'items';

    const KEY_TOTAL_COUNT = 'total_count';

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

    /**
     * Get items list.
     *
     * @return \Deity\CatalogApi\Api\Data\ProductInterface[]
     */
    public function getItems(): array;

    /**
     * Set items list.
     *
     * @param \Deity\CatalogApi\Api\Data\ProductInterface[] $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * Set total count.
     *
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount(int $totalCount);
}
