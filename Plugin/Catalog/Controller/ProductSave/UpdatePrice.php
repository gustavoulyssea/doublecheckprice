<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Plugin\Catalog\Controller\ProductSave;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterfaceFactory;
use GumNet\DoubleCheckPrice\Api\ProductPriceApprovalRepositoryInterface;
use GumNet\DoubleCheckPrice\Model\EmailSender;
use Magento\Backend\Model\Auth\Session;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Adminhtml\Product\Save;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;

class UpdatePrice
{
    public const PARAM_ID = 'id';
    public const PARAM_PRODUCT = 'product';
    public const PARAM_PRICE = 'price';
    public const SUCCESS_MESSAGE = 'Price update approval created successfully and is pending validation';

    /**
     * @param ProductPriceApprovalRepositoryInterface $productPriceApprovalRepository
     * @param ProductPriceApprovalInterfaceFactory $productPriceApprovalFactory
     * @param RequestInterface $request
     * @param ProductRepositoryInterface $productRepository
     * @param Session $authSession
     * @param EmailSender $emailSender
     * @param Manager $messageManager
     */
    public function __construct(
        private readonly ProductPriceApprovalRepositoryInterface $productPriceApprovalRepository,
        private readonly ProductPriceApprovalInterfaceFactory $productPriceApprovalFactory,
        private readonly RequestInterface $request,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly Session $authSession,
        private readonly EmailSender $emailSender,
        private readonly Manager $messageManager
    ) {
    }

    /**
     * @param Save $subject
     * @return void
     */
    public function beforeExecute(
        Save $subject
    ): void
    {
        $productParam = $this->request->getParam(self::PARAM_PRODUCT, []);
        if (!isset($productParam[self::PARAM_PRICE])) {
            return;
        }
        $productId = $this->request->getParam(self::PARAM_ID);
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return;
        }
        if ($productParam[self::PARAM_PRICE] === $product->getPrice()) {
            return;
        }
        $priceApproval = $this->createPriceApproval($product, (float)$productParam[self::PARAM_PRICE]);
        $this->emailSender->send($priceApproval);

        $this->messageManager->addSuccess(__(self::SUCCESS_MESSAGE));

        $productParam[self::PARAM_PRICE] = (string)$product->getPrice();
        $this->request->setPostValue(self::PARAM_PRODUCT, $productParam);
    }

    private function createPriceApproval(
        ProductInterface $product,
        float $newPrice
        ): ProductPriceApprovalInterface {
        /** @var ProductPriceApprovalInterface $productPriceApproval */
        $productPriceApproval = $this->productPriceApprovalFactory->create();
        $loggerInUser = $this->authSession->getUser();
        $productPriceApproval->setUsername($loggerInUser->getFirstName() . " " . $loggerInUser->getLastName());
        $productPriceApproval->setSku($product->getSku());
        $productPriceApproval->setAttribute(self::PARAM_PRICE);
        $productPriceApproval->setCreatedAt(date('Y-m-d H:i:s'));
        $productPriceApproval->setOldPrice((float)$product->getPrice());
        $productPriceApproval->setNewPrice($newPrice);
        $productPriceApproval->setStatus(ProductPriceApprovalInterface::STATUS_PENDING);
        return $this->productPriceApprovalRepository->save($productPriceApproval);
    }
}
