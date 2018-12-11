<?php
declare(strict_types=1);

namespace Deity\Breadcrumbs\Model;

use Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface;
use Deity\BreadcrumbsApi\Api\GetBreadcrumbsInterface;
use Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class GetBreadcrumbs
 * @package Deity\Breadcrumbs\Model
 */
class GetBreadcrumbs implements GetBreadcrumbsInterface
{


    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var BreadcrumbInterfaceFactory
     */
    private $breadcrumbFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * GetBreadcrumbs constructor.
     * @param UrlFinderInterface $urlFinder
     * @param BreadcrumbInterfaceFactory $breadcrumbFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        BreadcrumbInterfaceFactory $breadcrumbFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->urlFinder = $urlFinder;
        $this->breadcrumbFactory = $breadcrumbFactory;
        $this->storeManager = $storeManager;
    }


    /**
     * @param string $urlPath
     * @return \Deity\BreadcrumbsApi\Api\Data\BreadcrumbInterface[]
     * @throws NoSuchEntityException
     */
    public function execute(string $urlPath): array
    {

        $urlModel = $this->getUrlModel($urlPath);

        /**
         * @var BreadcrumbInterface $urlData
         */
        $breadcrumb = $this->breadcrumbFactory->create(
            [
                BreadcrumbInterface::NAME => $urlModel->getEntityType(),
                BreadcrumbInterface::URL_PATH => $urlModel->getRequestPath()
            ]
        );

        return [$breadcrumb];
    }

    /**
     * @param $path
     * @return UrlRewrite
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
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
}
