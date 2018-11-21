<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\Data;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Magento\Framework\Api\AbstractSimpleObject;
use Magento\Framework\Phrase;

class Filter extends AbstractSimpleObject implements FilterInterface
{
    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->_get(self::LABEL);
    }

    /**
     * @param string|Phrase $label
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_get(self::CODE);
    }

    /**
     * @param string $code
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @return int
     */
    public function getAttributeId()
    {
        return $this->_get(self::ATTRIBUTE_ID);
    }

    /**
     * @param int $attributeId
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setAttributeId($attributeId)
    {
        return $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @param string $type
     * @return \Deity\CatalogApi\Api\Data\FilterInterface;
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]
     */
    public function getOptions()
    {
        return $this->_get(self::OPTIONS);
    }

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface[] $options
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setOptions($options)
    {
        return $this->setData(self::OPTIONS, $options);
    }
}
