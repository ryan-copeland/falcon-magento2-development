<?php
declare(strict_types=1);

namespace Deity\MenuApi\Model;

use Deity\MenuApi\Api\Data\MenuInterface;
use Magento\Catalog\Model\Category;

/**
 * Interface ConvertCategoryToMenuItem
 *
 * @package Deity\MenuApi\Model
 */
interface ConvertCategoryToMenuInterface
{
    public function execute(Category $category): MenuInterface;
}
