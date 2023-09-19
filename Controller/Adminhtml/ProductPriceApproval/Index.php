<?php

/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Controller\Adminhtml\ProductPriceApproval;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__("Product Price Approval"));
        return $resultPage;
    }
}
