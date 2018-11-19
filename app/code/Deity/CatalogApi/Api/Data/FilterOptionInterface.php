<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

interface FilterOptionInterface
{
    const LABEL = 'label';
    const VALUE = 'value';
    const IS_ACTIVE = 'is_active';

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setValue($value);

    /**
     * @return boolean|null
     */
    public function getIsActive();

    /**
     * @param boolean $active
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setIsActive($active);
}
