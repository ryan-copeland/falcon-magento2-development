<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Phrase;

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
    public function getLabel();

    /**
     * @param string|Phrase $label
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setCode($code);

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]|null
     */
    public function getOptions();

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface[] $options
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setOptions($options);

    /**
     * @return int|null
     */
    public function getAttributeId();

    /**
     * @param int $attributeId
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setAttributeId($attributeId);

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @param string $type
     * @return \Deity\CatalogApi\Api\Data\FilterInterface;
     */
    public function setType($type);
}
