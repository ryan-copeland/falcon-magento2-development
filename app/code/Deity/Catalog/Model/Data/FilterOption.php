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
    public function getLabel(): string
    {
        return (string)$this->_get(self::LABEL);
    }

    /**
     * @param string $label
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setLabel(string $label): FilterOptionInterface
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->_get(self::VALUE);
    }

    /**
     * @param string $value
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setValue(string $value): FilterOptionInterface
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return (int)$this->_get(self::COUNT);
    }

    /**
     * @param int $count
     * @return \Deity\CatalogApi\Api\Data\FilterOptionInterface
     */
    public function setCount(int $count): FilterOptionInterface
    {
        return $this->setData(self::COUNT, $count);
    }
}
