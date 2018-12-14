<?php
declare(strict_types=1);

namespace Deity\Menu\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Deity\MenuApi\Api\Data\MenuInterfaceFactory;
use Deity\MenuApi\Api\GetMenuInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Topmenu;

/**
 * Class GetMenu
 * @package Deity\Menu\Model
 */
class GetMenu implements GetMenuInterface
{
    /**
     * @var MenuInterfaceFactory
     */
    protected $menuFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * MenuRepository constructor.
     * @param MenuInterfaceFactory $menuFactory
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param BlockFactory $blockFactory yes, really, check further description below
     */
    public function __construct(
        MenuInterfaceFactory $menuFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        BlockFactory $blockFactory
    )
    {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->blockFactory = $blockFactory;
        $this->menuFactory = $menuFactory;
    }

    /**
     * @return \Deity\MenuApi\Api\Data\MenuInterface[]
     */
    public function execute(): array
    {
        /** @var Node $menuTree */
        $menuTree = $this->getMenuFromTopmenuBlock();

        /** @var MenuInterface[] $items */
        $items = $this->convertMenuNodesToMenuItems($menuTree);

        return $items;
    }

    /**
     * Convert node tree from topmenu block into array of MenuInterface objects
     *
     * @param Node $node
     * @return MenuInterface[]
     */
    protected function convertMenuNodesToMenuItems(Node $node)
    {
        $items = [];
        foreach($node->getChildren() as $childNode) { /** @var Node $childNode */
            /** @var MenuInterface $menuItem */
            $menuItem = $this->menuFactory->create();

            $menuItem->setName($childNode->getName());
            $menuItem->setUrlPath($childNode->getUrl());

            if ($childNode->hasChildren()) {
                $children = $this->convertMenuNodesToMenuItems($childNode);
                $menuItem->setChildren($children);
            }
            $items[] = $menuItem;
        }

        return  $items;
    }

    /**
     * @return Node
     */
    protected function getMenuFromTopmenuBlock()
    {
        /** @var Topmenu $topMenuBlock */
        $topMenuBlock = $this->blockFactory->createBlock(Topmenu::class);
        //need to call this for plugins to work but we don't care about the generated html,
        // it's just few ms of the processor time we need to waste
        $topMenuBlock->getHtml();

        //now we how menu tree to work with
        return $topMenuBlock->getMenu();
    }
}