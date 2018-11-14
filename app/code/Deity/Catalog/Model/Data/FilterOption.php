<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class FilterOption extends AbstractSimpleObject implements FilterOptionInterface
{
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_getData(self::LABEL);
    }

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_getData(self::VALUE);
    }

    /**
     * @param string $value
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->_getData(self::ACTIVE);
    }

    /**
     * @param boolean $active
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setActive($active)
    {
        return $this->setData(self::ACTIVE, $active);
    }
}