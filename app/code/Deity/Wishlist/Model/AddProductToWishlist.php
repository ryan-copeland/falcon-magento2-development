<?php
declare(strict_types=1);

namespace Deity\Wishlist\Model;

use Deity\Customer\Model\Security\CustomerContext;
use Deity\WishlistApi\Api\AddProductToWishlistInterface;
use Deity\WishlistApi\Api\Data\WishlistProductRequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\AuthorizationException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Wishlist\Model\WishlistFactory;
use Psr\Log\LoggerInterface;

class AddProductToWishlist implements AddProductToWishlistInterface
{
    /**
     * @var CustomerContext
     */
    protected $customerContext;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;
    /**
     * @var WishlistFactory
     */
    protected $wishlistFactory;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AddProductToWishlist constructor.
     * @param CustomerContext $customerContext
     * @param DataObjectFactory $dataObjectFactory
     * @param ProductRepositoryInterface $productRepository
     * @param LoggerInterface $logger
     * @param WishlistFactory $wishlistFactory
     */
    public function __construct(
        CustomerContext $customerContext,
        DataObjectFactory $dataObjectFactory,
        ProductRepositoryInterface $productRepository,
        LoggerInterface $logger,
        WishlistFactory $wishlistFactory
    ) {
        $this->customerContext = $customerContext;
        $this->productRepository = $productRepository;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->wishlistFactory = $wishlistFactory;
        $this->logger = $logger;
    }

    /**
     * Add product to customer wishlist.
     *
     * @param WishlistProductRequestInterface $addToWishlist
     * @return bool
     * @throws AuthorizationException|NoSuchEntityException|LocalizedException
     */
    public function execute(WishlistProductRequestInterface $addToWishlist)
    {
        $this->customerContext->checkCustomerContext();
        $customerId = $this->customerContext->getCurrentCustomerId();
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId, true);

        try {
            /** @var Product $product */
            $product = $this->productRepository->getById($addToWishlist->getProductId());
            $buyRequest = $this->dataObjectFactory->create();
            $buyRequest->setData('product', $addToWishlist->getProductId())
                ->setData('qty', $addToWishlist->getQty());

            if (!empty($addToWishlist->getSuperAttribute())) {
                $buyRequest->setData('super_attribute', $addToWishlist->getSuperAttribute());
            }

            $wishlist->addNewItem($product, $buyRequest);
        } catch (NoSuchEntityException | LocalizedException $e) {
            $this->logger->error($e);
            return false;
        }

        return true;
    }
}
