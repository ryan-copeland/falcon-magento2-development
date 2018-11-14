<?php
declare(strict_types=1);

namespace Deity\CatalogApi\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

/**
 * Interface Product
 * @package Deity\CatalogApi\Api\Data
 */
interface ProductInterface extends ExtensibleDataInterface
{
    const SKU = 'sku';

    const NAME = 'name';

    const IMAGE = 'image';

    const URL_PATH = 'url_path';

    /**
     * @return \Deity\CatalogApi\Api\Data\ProductExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \Deity\CatalogApi\Api\Data\ProductExtensionInterface $extensionAttributes
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setExtensionAttributes(ProductExtensionInterface $extensionAttributes);

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $sku
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setSku(string $sku): ProductInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setName(string $name): ProductInterface;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @param string $image
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setImage(string $image): ProductInterface;

    /**
     * @return string
     */
    public function getUrlPath(): string;

    /**
     * @param string $urlPath
     * @return \Deity\CatalogApi\Api\Data\ProductInterface
     */
    public function setUrlPath(string $urlPath): ProductInterface;
}
