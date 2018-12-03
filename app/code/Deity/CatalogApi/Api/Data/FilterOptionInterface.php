<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface FilterOptionInterface
{
    const LABEL = 'label';
    const VALUE = 'value';
    const COUNT = 'count';

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setLabel(string $label): FilterOptionInterface;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param string $value
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setValue(string $value): FilterOptionInterface;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @param int $count
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setCount(int $count): FilterOptionInterface;
}
