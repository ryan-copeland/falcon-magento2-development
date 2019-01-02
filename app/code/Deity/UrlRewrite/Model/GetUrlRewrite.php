<?php
declare(strict_types=1);

namespace Deity\UrlRewrite\Model;

use Deity\UrlRewriteApi\Api\ConvertEntityIdToUniqueKeyInterface;
use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterface;
use Deity\UrlRewriteApi\Api\Data\UrlRewriteInterfaceFactory;
use Deity\UrlRewriteApi\Api\GetUrlRewriteInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * @package Deity\UrlRewrite\Model
 */
class GetUrlRewrite implements GetUrlRewriteInterface
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var UrlRewriteInterfaceFactory
     */
    private $urlRewriteFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ConvertEntityIdToUniqueKeyInterface[]
     */
    private $commandsPerEntityType;

    /**
     * Url constructor.
     *
     * @param UrlFinderInterface $urlFinder
     * @param UrlRewriteInterfaceFactory $urlRewriteFactory
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     * @param ProductRepositoryInterface $productRepository
     * @param array $commandsPerEntityType
     * @throws LocalizedException
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        UrlRewriteInterfaceFactory $urlRewriteFactory,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        ProductRepositoryInterface $productRepository,
        $commandsPerEntityType = []
    ) {
        $this->urlFinder = $urlFinder;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->storeManager = $storeManager;

        foreach ($commandsPerEntityType as $command) {
            if (!$command instanceof ConvertEntityIdToUniqueKeyInterface) {
                throw new LocalizedException(
                    __(
                        'Convert Entity class must implement %interface.',
                        ['interface' => ConvertEntityIdToUniqueKeyInterface::class]
                    )
                );
            }
        }

        $this->commandsPerEntityType = $commandsPerEntityType;
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $url
     * @return UrlRewriteInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(string $url): UrlRewriteInterface
    {
        $urlModel = $this->getUrlModel($url);

        /**
         * @var UrlRewriteInterface $urlData
         */
        $urlData = $this->urlRewriteFactory->create();
        $urlData->setEntityType($this->sanitizeType($urlModel->getEntityType()));
        $urlData->setEntityId((string)$urlModel->getEntityId());

        $urlData->setCanonicalUrl($this->getCanonicalUrl($urlModel));

        if (isset($this->commandsPerEntityType[$urlModel->getEntityType()])) {
            $this->commandsPerEntityType[$urlModel->getEntityType()]->execute($urlData);
        }

        return $urlData;
    }

    /**
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlModel
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getCanonicalUrl(UrlRewrite $urlModel)
    {
        switch($urlModel->getEntityType()){
            case 'product':
                $entity = $this->productRepository->getById($urlModel->getEntityId());
                return $entity->getUrlModel()->getUrl($entity, ['_ignore_category' => true]);
            default:
                return $this->urlBuilder->getDirectUrl($urlModel->getRequestPath());
        }
    }

    /**
     * @param $path
     * @return UrlRewrite
     * @throws NoSuchEntityException
     */
    private function getUrlModel($path)
    {
        $urlModel = $this->urlFinder->findOneByData(
            [
                'request_path' => $path,
                'store_id'  => $this->storeManager->getStore()->getId()
            ]
        );

        if (!$urlModel) {
            throw new NoSuchEntityException(__('Requested url doesn\'t exist'));
        }

        return $urlModel;
    }

    /**
     * Sanitize the type to fit schema specifications
     *
     * @param  string $type
     * @return string
     */
    private function sanitizeType(string $type) : string
    {
        return strtoupper(str_replace('-', '_', $type));
    }
}
