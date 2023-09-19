<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Test\Unit\Model;

use GumNet\DoubleCheckPrice\Model\Data\ProductPriceApproval as ProductPriceDataModel;
use GumNet\DoubleCheckPrice\Model\ProductPriceApproval;
use GumNet\DoubleCheckPrice\Model\ProductPriceApprovalRepository;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval as ProductPriceApprovalResource;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval\Collection;

class ProductPriceApprovalRepositoryTest extends TestCase
{

    private MockObject $resource;
    private MockObject $productPriceApprovalFactory;
    private MockObject $extensibleDataObjectConverter;
    private MockObject $collectionFactory;
    private MockObject $collection;
    private MockObject $productPriceApproval;
    private MockObject $productPriceApprovalDataModel;

    private ProductPriceApprovalRepository $productPriceApprovalRepository;

    protected function setUp(): void
    {
        $this->resource = $this->createMock(ProductPriceApprovalResource::class);
        $this->productPriceApprovalFactory = $this->getMockBuilder(
            'GumNet\DoubleCheckPrice\Model\ProductPriceApprovalFactory'
        )->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $this->extensibleDataObjectConverter = $this->createMock(ExtensibleDataObjectConverter::class);
        $this->collectionFactory = $this->getMockBuilder(
            'GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval\CollectionFactory'
        )->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $this->collection = $this->createMock(Collection::class);
        $this->productPriceApproval = $this->createMock(ProductPriceApproval::class);
        $this->productPriceApprovalDataModel = $this->createMock(ProductPriceDataModel::class);

        $this->productPriceApprovalRepository = new ProductPriceApprovalRepository(
            $this->resource,
            $this->productPriceApprovalFactory,
            $this->extensibleDataObjectConverter,
            $this->collectionFactory
        );
    }

    public function testSave(): void
    {
        $this->prepareSave();
        $this->assertSame(
            $this->productPriceApprovalDataModel,
            $this->productPriceApprovalRepository->save($this->productPriceApprovalDataModel)
        );
    }

    private function prepareSave(): void
    {
        $this->extensibleDataObjectConverter->expects($this->once())
            ->method('toNestedArray')
            ->willReturn([]);
        $this->productPriceApprovalFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->productPriceApproval);
        $this->productPriceApproval->expects($this->once())
            ->method('setData')
            ->willReturnSelf();
        $this->resource->expects($this->once())
            ->method('save')
            ->willReturn($this->productPriceApproval);
    }

    public function testGetThrowException(): void
    {
        $this->prepareGet(true);
        $this->expectException(NoSuchEntityException::class);
        $this->productPriceApprovalRepository->get(1);
    }

    public function testGet(): void
    {
        $this->prepareGet();
        $this->assertSame($this->productPriceApprovalDataModel, $this->productPriceApprovalRepository->get(1));
    }

    public function prepareGet($throwException = false): void
    {
        $this->productPriceApprovalFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->productPriceApproval);
        $this->productPriceApproval->expects($this->once())
            ->method('load')
            ->willReturnSelf();
        if (!$throwException) {
            $this->productPriceApproval->expects($this->once())
                ->method('getId')
                ->willReturn(1);
            $this->productPriceApproval->expects($this->once())
                ->method('getDataModel')
                ->willReturn($this->productPriceApprovalDataModel);
        } else {
            $this->productPriceApproval->expects($this->once())
                ->method('getId')
                ->willThrowException(new NoSuchEntityException());
        }
    }

    public function testGetPending(): void
    {
        $this->prepareGetPending();
        $this->assertSame([$this->productPriceApprovalDataModel], $this->productPriceApprovalRepository->getPending());
    }

    private function prepareGetPending(): void
    {
        $this->collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->collection);
        $this->collection->expects($this->once())
            ->method('addFieldToFilter')
            ->willReturnSelf();
        $this->collection->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->productPriceApprovalDataModel]);
    }
}
