<?php
namespace Deity\MagentoApi\Api\Integration\Data;

use Deity\MagentoApi\Api\Integration\Data\CustomerTokenExtensionInterface;
use Magento\Framework\Api\ExtensibleDataInterface;

interface CustomerTokenInterface extends ExtensibleDataInterface
{
    const TOKEN = 'token';

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param string $token
     * @return \Deity\MagentoApi\Api\Integration\Data\AdminTokenInterface
     */
    public function setToken($token);

    /**
     * @return \Deity\MagentoApi\Api\Integration\Data\CustomerTokenExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Deity\MagentoApi\Api\Integration\Data\CustomerTokenExtensionInterface $extensionAttributes
     * @return \Deity\MagentoApi\Api\Integration\Data\AdminTokenInterface
     */
    public function setExtensionAttributes(CustomerTokenExtensionInterface $extensionAttributes);
}