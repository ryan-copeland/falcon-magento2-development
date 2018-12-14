<?php
declare(strict_types=1);

namespace Deity\Menu\Model\Data;

use Deity\MenuApi\Api\Data\MenuInterface;

class Menu implements MenuInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var boolean
     */
    private $hasActive;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var int
     */
    private $level;

    /**
     * @var boolean
     */
    private $isFirst;

    /**
     * @var boolean
     */
    private $isLast;

    /**
     * @var string
     */
    private $positionClass;

    /**
     * @var MenuInterface[]
     */
    private $children = [];

    /**
     * Menu constructor.
     * @param int $id
     * @param string $name
     * @param string $url
     * @param bool $hasActive
     * @param bool $isActive
     * @param int $level
     * @param bool $isFirst
     * @param bool $isLast
     * @param string $positionClass
     */
    public function __construct(
        int $id,
        string $name,
        string $url,
        bool $hasActive,
        bool $isActive,
        int $level,
        bool $isFirst,
        bool $isLast,
        string $positionClass
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->url = $url;
        $this->hasActive = $hasActive;
        $this->isActive = $isActive;
        $this->level = $level;
        $this->isFirst = $isFirst;
        $this->isLast = $isLast;
        $this->positionClass = $positionClass;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }


    /**
     * @return boolean
     */
    public function getHasActive(): bool
    {
        return $this->hasActive;
    }

    /**
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return boolean
     */
    public function getIsFirst(): bool
    {
        return $this->isFirst;
    }

    /**
     * @return boolean
     */
    public function getIsLast(): bool
    {
        return $this->isLast;
    }

    /**
     * @return string
     */
    public function getPositionClass(): string
    {
        return $this->positionClass;
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
}
