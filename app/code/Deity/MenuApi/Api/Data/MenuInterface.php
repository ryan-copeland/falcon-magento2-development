<?php
declare(strict_types=1);

namespace Deity\MenuApi\Api\Data;

/**
 * Menu interface
 *
 * @package Deity\MenuApi\Api\Data
 */
interface MenuInterface
{
    const CHILDREN = 'children';
    const NAME = 'name';
    const ID = 'id';
    const URL = 'url';
    const HAS_ACTIVE = 'hasActive';
    const IS_ACTIVE = 'isActive';
    const LEVEL = 'level';
    const IS_FIRST = 'isFirst';
    const IS_LAST = 'isLast';
    const POSITION_CLASS = 'positionClass';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return boolean
     */
    public function getHasActive(): bool;

    /**
     * @return boolean
     */
    public function getIsActive(): bool;

    /**
     * @return int
     */
    public function getLevel(): int;

    /**
     * @return boolean
     */
    public function getIsFirst(): bool;

    /**
     * @return boolean
     */
    public function getIsLast(): bool;

    /**
     * @return string
     */
    public function getPositionClass(): string;
    
    /**
     * @return \Deity\MenuApi\Api\Data\MenuInterface[]
     */
    public function getChildren(): array;

    /**
     * @param array $children
     * @return void
     */
    public function setChildren(array $children);
}
