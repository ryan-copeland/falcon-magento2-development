<?php
declare(strict_types=1);

namespace Deity\Catalog\Model;

use Deity\CatalogApi\Api\Data\FilterInterface;
use Deity\CatalogApi\Api\Data\FilterInterfaceFactory;
use Deity\CatalogApi\Api\Data\FilterOptionInterface;
use Deity\CatalogApi\Api\Data\FilterOptionInterfaceFactory;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Item;

/**
 * Class ProductFilterProvider
 * @package Deity\Catalog\Model
 */
class ProductFilterProvider implements \Deity\CatalogApi\Api\ProductFilterProviderInterface
{

    /**
     * @var Layer\FilterList
     */
    private $filterList;

    /**
     * @var FilterInterfaceFactory;
     */
    private $filterFactory;

    /**
     * @var FilterOptionInterfaceFactory
     */
    private $filterOptionFactory;

    /**
     * ProductFilterProvider constructor.
     * @param Layer\FilterList $filterList
     * @param FilterInterfaceFactory $filterFactory
     * @param FilterOptionInterfaceFactory $filterOptionFactory
     */
    public function __construct(
        Layer\FilterList $filterList,
        FilterInterfaceFactory $filterFactory,
        FilterOptionInterfaceFactory $filterOptionFactory
    ) {
        $this->filterList = $filterList;
        $this->filterOptionFactory = $filterOptionFactory;
        $this->filterFactory = $filterFactory;
    }

    /**
     * @param Layer $layer
     * @return \Deity\CatalogApi\Api\Data\FilterInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilterList(Layer $layer): array
    {
        /** @var AbstractFilter[] $magentoFilters */
        $magentoFilters = $this->filterList->getFilters($layer);
        $resultFilters = [];
        foreach ($magentoFilters as $magentoFilter) {
            if (!$magentoFilter->getItemsCount()) {
                continue;
            }
            /** @var FilterInterface $filterObject */
            $filterObject = $this->filterFactory->create();
            $filterObject->setLabel($magentoFilter->getName());
            if ($magentoFilter->getRequestVar() == 'cat') {
                $filterObject->setCode($magentoFilter->getRequestVar());
                $filterObject->setType('int');
            } else {
                $filterObject->setAttributeId($magentoFilter->getAttributeModel()->getAttributeId());
                $filterObject->setCode($magentoFilter->getAttributeModel()->getAttributeCode());
                $filterObject->setType($magentoFilter->getAttributeModel()->getBackendType());
            }

            $magentoOptions = $magentoFilter->getItems();
            $filterOptions = [];
            /** @var Item $magentoOption */
            foreach ($magentoOptions as $magentoOption) {
                /** @var FilterOptionInterface $filterOption */
                $filterOption =$this->filterOptionFactory->create();
                $filterOption->setLabel($magentoOption->getData('label'));
                $filterOption->setValue($magentoOption->getValueString());
                $filterOption->setCount((int)$magentoOption->getData('count'));
                $filterOptions[] = $filterOption;
            }
            $filterObject->setOptions($filterOptions);

            $resultFilters[] = $filterObject;
        }
        return $resultFilters;
    }
}
