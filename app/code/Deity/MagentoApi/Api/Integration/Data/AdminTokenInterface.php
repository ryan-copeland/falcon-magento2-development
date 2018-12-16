<?php
namespace Deity\MagentoApi\Api\Integration\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface AdminTokenInterface extends ExtensibleDataInterface
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
     * @return \Deity\MagentoApi\Api\Integration\Data\AdminTokenExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Deity\MagentoApi\Api\Integration\Data\AdminTokenExtensionInterface $extensionAttributes
     * @return \Deity\MagentoApi\Api\Integration\Data\AdminTokenInterface
     */
    public function setExtensionAttributes($extensionAttributes);
}