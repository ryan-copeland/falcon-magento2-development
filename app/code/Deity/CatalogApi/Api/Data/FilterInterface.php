<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface FilterInterface
{
    const LABEL = 'label';
    const CODE  = 'code';
    const OPTIONS = 'options';
    const ATTRIBUTE_ID = 'attribute_id';
    const TYPE = 'type';

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setLabel(string $label): FilterInterface;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string $code
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setCode(string $code): FilterInterface;

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]|null
     */
    public function getOptions(): array;

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface[] $options
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setOptions(array $options): FilterInterface;

    /**
     * @return int
     */
    public function getAttributeId(): int;

    /**
     * @param int $attributeId
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setAttributeId(int $attributeId): FilterInterface;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return \Deity\CatalogApi\Api\Data\FilterInterface;
     */
    public function setType(string $type): FilterInterface;
}
