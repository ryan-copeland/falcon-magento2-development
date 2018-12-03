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
    public function getLabel(): string
    {
        return (string)$this->_get(self::LABEL);
    }

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setLabel(string $label): FilterInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string)$this->_get(self::CODE);
    }

    /**
     * @param string $code
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setCode(string $code): FilterInterface
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @return int
     */
    public function getAttributeId(): int
    {
        return (int)$this->_get(self::ATTRIBUTE_ID);
    }

    /**
     * @param int $attributeId
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setAttributeId(int $attributeId): FilterInterface
    {
        return $this->setData(self::ATTRIBUTE_ID, $attributeId);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return (string)$this->_get(self::TYPE);
    }

    /**
     * @param string $type
     * @return \Deity\CatalogApi\Api\Data\FilterInterface;
     */
    public function setType(string $type): FilterInterface
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->_get(self::OPTIONS);
    }

    /**
     * @param \Deity\CatalogApi\Api\Data\FilterOptionInterface[] $options
     * @return \Deity\CatalogApi\Api\Data\FilterInterface
     */
    public function setOptions(array $options): FilterInterface
    {
        return $this->setData(self::OPTIONS, $options);
    }
}
