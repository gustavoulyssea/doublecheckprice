<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Controller\Adminhtml\Index;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\ProductPriceApprovalRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Psr\Log\LoggerInterface;

class Accept extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'GumNet_DoubleCheckPrice::product_price_approval';
    public const MESSAGE_SUCCESS = 'Price update accepted';
    public const PARAM_ID = 'id';
    public const PATH_INDEX = 'product_price_approval/index/index';

    public function __construct(
        Context $context,
        private readonly RedirectFactory $redirectFactory,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductPriceApprovalRepositoryInterface $productPriceApprovalRepository,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    /**
     * Accept action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $id = $this->_request->getParam(self::PARAM_ID, '');
            $productPriceApproval = $this->productPriceApprovalRepository->get((int)$id);
            $product = $this->productRepository->get($productPriceApproval->getSku());
            $product->setPrice($productPriceApproval->getNewPrice());
            $this->productRepository->save($product);
            $productPriceApproval->setStatus(ProductPriceApprovalInterface::STATUS_ACCEPTED);
            $this->productPriceApprovalRepository->save($productPriceApproval);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
        $this->getMessageManager()->addSuccess(__(self::MESSAGE_SUCCESS));
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath(self::PATH_INDEX);
    }
}
