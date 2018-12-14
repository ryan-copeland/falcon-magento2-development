<?php
declare(strict_types=1);

namespace Deity\Menu\Model\Data;

use Deity\MenuApi\Api\Data\MenuExtensionInterface;
use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

class Menu extends AbstractExtensibleModel implements MenuInterface
{

    /**
     * @var MenuInterface[]
     */
    private $children = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->_getData(self::NAME);
    }

    /**
     * @param string $name
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setName(string $name): MenuInterface
    {
        $this->setData(self::NAME, $name);
        return $this;
    }

    /**
     * @return string
     */
    public function getUrlPath(): string
    {
        return (string)$this->_getData(self::URL_PATH);
    }

    /**
     * @param string $urlPath
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setUrlPath(string $urlPath): MenuInterface
    {
        $this->setData(self::URL_PATH, $urlPath);
        return $this;
    }

    /**
     * @return string
     */
    public function getCssClass(): string
    {
        return (string)$this->_getData(self::CSS_CLASS);
    }

    /**
     * @param string $cssClass
     * @return MenuInterface
     */
    public function setCssClass(string $cssClass): MenuInterface
    {
        $this->setData(self::CSS_CLASS, $cssClass);
        return $this;
    }

    /**
     * @return MenuInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     * @return void
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return \Deity\MenuApi\Api\Data\MenuExtensionInterface
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->extensionAttributesFactory->create(MenuInterface::class);
            $this->_setExtensionAttributes($extensionAttributes);
            return $extensionAttributes;
        }
        return $extensionAttributes;
    }

    /**
     * @param \Deity\MenuApi\Api\Data\MenuExtensionInterface $extensionAttributes
     * @return \Deity\MenuApi\Api\Data\MenuInterface
     */
    public function setExtensionAttributes(MenuExtensionInterface $extensionAttributes): MenuInterface
    {
        $this->_setExtensionAttributes($extensionAttributes);
        return $this;
    }
}
