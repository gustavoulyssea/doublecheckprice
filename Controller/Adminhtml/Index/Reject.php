<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Controller\Adminhtml\Index;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\ProductPriceApprovalRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;
use Psr\Log\LoggerInterface;

class Reject extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'GumNet_DoubleCheckPrice::product_price_approval';
    public const MESSAGE_SUCCESS = 'Price update accepted';
    public const PARAM_ID = 'id';
    public const PATH_INDEX = 'product_price_approval/index/index';

    /**
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param ProductPriceApprovalRepositoryInterface $productPriceApprovalRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        private readonly RedirectFactory $redirectFactory,
        private readonly ProductPriceApprovalRepositoryInterface $productPriceApprovalRepository,
        private readonly LoggerInterface $logger,
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
            $productPriceApproval->setStatus(ProductPriceApprovalInterface::STATUS_REJECTED);
            $this->productPriceApprovalRepository->save($productPriceApproval);
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
        $this->getMessageManager()->addSuccess(__(self::MESSAGE_SUCCESS));
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath(self::PATH_INDEX);
    }
}
