<?php
declare(strict_types=1);

namespace Deity\Sales\Model;

use Deity\Sales\Model\ResourceModel\OrderIdMask as ResourceOrderIdMask;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * OrderIdMask model
 *
 * @method string getMaskedId()
 * @method OrderIdMask setMaskedId()
 * @method int getOrderId()
 * @method OrderIdMask setOrderId()
 */
class OrderIdMask extends AbstractModel
{
    /**
     * @var Random
     */
    protected $randomDataGenerator;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param Random $randomDataGenerator
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Random $randomDataGenerator,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->randomDataGenerator = $randomDataGenerator;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Initialize resource
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceOrderIdMask::class);
    }

    /**
     * Initialize quote identifier before save
     *
     * @return $this
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        parent::beforeSave();
        if (!$this->getData(ResourceOrderIdMask::MASKED_ID_FIELD_NAME)) {
            $this->setData(ResourceOrderIdMask::MASKED_ID_FIELD_NAME, $this->randomDataGenerator->getUniqueHash());
        }
        return $this;
    }
}
