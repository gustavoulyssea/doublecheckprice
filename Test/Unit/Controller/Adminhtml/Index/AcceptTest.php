<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Test\Unit\Controller\Adminhtml\Index;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\ProductPriceApprovalRepositoryInterface;
use GumNet\DoubleCheckPrice\Controller\Adminhtml\Index\Accept;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AcceptTest extends TestCase
{
    private MockObject $context;
    private MockObject $redirectFactory;
    private MockObject $productRepository;
    private MockObject $productPriceApprovalRepository;
    private MockObject $logger;
    private MockObject $request;
    private MockObject $productPriceApproval;
    private MockObject $product;
    private MockObject $messageManager;
    private MockObject $redirect;

    private Accept $accept;

    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->redirectFactory = $this->getMockBuilder('Magento\Framework\Controller\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productPriceApprovalRepository = $this->createMock(ProductPriceApprovalRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->productPriceApproval = $this->createMock(ProductPriceApprovalInterface::class);
        $this->product = $this->createMock(ProductInterface::class);
        $this->messageManager = $this->createMock(ManagerInterface::class);
        $this->redirect = $this->createMock(Redirect::class);

        $this->accept = new Accept(
            $this->context,
            $this->redirectFactory,
            $this->productRepository,
            $this->productPriceApprovalRepository,
            $this->logger
        );
    }

    public function testExecute(): void
    {
        $this->prepareExecute();
        $this->assertSame($this->redirect, $this->accept->execute());
    }

    private function prepareExecute(): void
    {
        $this->context->expects($this->once())
            ->method('getRequest')
            ->willReturn($this->request);
        $this->request->expects($this->once())
            ->method('getParam')
            ->willReturn('1');
        $this->productPriceApprovalRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->productPriceApproval);
        $this->productPriceApproval->expects($this->once())
            ->method('getSku')
            ->willReturn('sku');
        $this->productRepository->expects($this->once())
            ->method('get')
            ->willReturn($this->product);
        $this->product->expects($this->once())
            ->method('setPrice')
            ->willReturnSelf();
        $this->productRepository->expects($this->once())
            ->method('save')
            ->willReturn($this->product);
        $this->productPriceApproval->expects($this->once())
            ->method('setStatus')
            ->willReturnSelf();
        $this->productPriceApprovalRepository->expects($this->once())
            ->method('save')
            ->willReturn($this->productPriceApproval);
        $this->messageManager->expects($this->once())
            ->method('addSuccess')
            ->willReturnSelf();
        $this->redirectFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->redirect);
        $this->redirect->expects($this->once())
            ->method('setPath')
            ->willReturnSelf();
    }
}
