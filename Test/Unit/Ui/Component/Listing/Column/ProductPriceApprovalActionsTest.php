<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Test\Unit\Ui\Component\Listing\Column;

use GumNet\DoubleCheckPrice\Model\EmailSender;
use GumNet\DoubleCheckPrice\Ui\Component\Listing\Column\ProductPriceApprovalActions;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductPriceApprovalActionsTest extends TestCase
{
    private MockObject $urlBuilder;
    private MockObject $context;
    private MockObject $uiComponentFactory;
    private ProductPriceApprovalActions $productPriceApprovalActions;

    private EmailSender $emailSender;

    protected function setUp(): void
    {
        $this->urlBuilder = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->context = $this->createMock(ContextInterface::class);
        $this->uiComponentFactory = $this->createMock(UiComponentFactory::class);

        $this->productPriceApprovalActions = new ProductPriceApprovalActions(
            $this->urlBuilder,
            $this->context,
            $this->uiComponentFactory,
            [],
            ['name' => 'name']
        );
    }

    public function testPrepareDataSource(): void
    {
        $this->preparePrepareDataSource();
        $inputDataSource = [
            'data' => [
                'items' => [
                    [
                        'entity_id' => 1
                    ]
                ]
            ]
        ];
        $outputDataSource = $this->productPriceApprovalActions->prepareDataSource($inputDataSource);
        $this->assertEquals(
            'http://test.com/',
            $outputDataSource['data']['items'][0]['name']['accept']['href']
        );
    }
    private function preparePrepareDataSource(): void
    {
        $this->urlBuilder->expects($this->exactly(2))
            ->method('getUrl')
            ->willReturn('http://test.com/');
    }
}
