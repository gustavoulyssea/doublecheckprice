<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Test\Unit\Model;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Model\EmailSender;
use Magento\Email\Model\ResourceModel\Template\Collection;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\TransportInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Magento\Email\Model\Template;

class EmailSenderTest extends TestCase
{
    private MockObject $transportBuilder;
    private MockObject $storeManager;
    private MockObject $scopeConfig;
    private MockObject $logger;
    private MockObject $templateCollectionFactory;
    private MockObject $templateCollection;
    private MockObject $template;
    private MockObject $store;
    private MockObject $priceApproval;
    private MockObject $transport;


    private EmailSender $emailSender;

    protected function setUp(): void
    {
        $this->transportBuilder = $this->createMock(TransportBuilder::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->templateCollectionFactory = $this->getMockBuilder(
            'Magento\Email\Model\ResourceModel\Template\CollectionFactory'
        )
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $this->templateCollection = $this->createMock(Collection::class);
        $this->template = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()
            ->addMethods(['getTemplateId'])
            ->getMock();
        $this->store = $this->createMock(StoreInterface::class);
        $this->priceApproval = $this->createMock(ProductPriceApprovalInterface::class);
        $this->transport = $this->createMock(TransportInterface::class);

        $this->emailSender = new EmailSender(
            $this->transportBuilder,
            $this->storeManager,
            $this->scopeConfig,
            $this->logger,
            $this->templateCollectionFactory
        );
    }

    public function testSend(): void
    {
        $this->prepareSend();
        $this->assertNull($this->emailSender->send($this->priceApproval));
    }

    private function prepareSend(): void
    {
        $this->prepareGetTemplateId();
        $this->scopeConfig->expects($this->exactly(4))
            ->method('getValue')
            ->willReturn('1');
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->willReturnSelf();
        $this->storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($this->store);
        $this->store->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->willReturnSelf();
        $this->priceApproval->expects($this->once())
            ->method('getSku')
            ->willReturn('sku');
        $this->priceApproval->expects($this->once())
            ->method('getUsername')
            ->willReturn('my name');
        $this->priceApproval->expects($this->once())
            ->method('getCreatedAt')
            ->willReturn('2022-01-01 00:00:00');
        $this->priceApproval->expects($this->once())
            ->method('getOldPrice')
            ->willReturn(2.0);
        $this->priceApproval->expects($this->once())
            ->method('getNewPrice')
            ->willReturn(1.0);
        $this->transportBuilder->expects($this->once())
        ->method('setFromByScope')
        ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($this->transport);
        $this->transport->expects($this->once())
            ->method('sendMessage');
    }

    private function prepareGetTemplateId(): void
    {
        $this->templateCollectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->templateCollection);
        $this->templateCollection->expects($this->once())
            ->method('addFieldToFilter')
            ->willReturnSelf();
        $this->templateCollection->expects($this->once())
            ->method('getFirstItem')
            ->willReturn($this->template);
        $this->template->expects($this->once())
            ->method('getTemplateId')
            ->willReturn('1');
    }
}
