<?php
declare(strict_types=1);

namespace Deity\Catalog\Model\ResourceModel\Product;

class Collection extends \Magento\Catalog\Model\ResourceModel\Product\Collection
{
    /**
     * @inheritdoc
     */
    protected function _afterLoad()
    {
        $this->addCategoryIds();

        return parent::_afterLoad();
    }
}
