<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\ProductPriceApprovalRepositoryInterface;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval as ProductPriceApprovalResource;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval\Collection;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval\CollectionFactory;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @codeCoverageIgnore
 */
class ProductPriceApprovalRepository implements ProductPriceApprovalRepositoryInterface
{
    /**
     * @param ProductPriceApprovalResource $resource
     * @param ProductPriceApprovalFactory $productPriceApprovalFactory
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        private readonly ProductPriceApprovalResource $resource,
        private readonly ProductPriceApprovalFactory $productPriceApprovalFactory,
        private readonly ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        private readonly CollectionFactory $collectionFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function save(ProductPriceApprovalInterface $productPriceApproval): ProductPriceApprovalInterface
    {
        $productPriceApprovalData = $this->extensibleDataObjectConverter->toNestedArray(
            $productPriceApproval,
            [],
            ProductPriceApprovalInterface::class
        );

        $productPriceApprovalModel = $this->productPriceApprovalFactory->create()
            ->setData($productPriceApprovalData);

        $this->resource->save($productPriceApprovalModel);

        return $productPriceApproval;
    }

    /**
     * @inheritDoc
     */
    public function get(int $entityId): ProductPriceApprovalInterface
    {
        /** @var ProductPriceApproval $productPriceApproval */
        $productPriceApproval = $this->productPriceApprovalFactory->create()
            ->load($entityId);

        if ($productPriceApproval->getId() === null) {
            throw NoSuchEntityException::singleField('entity_id', $entityId);
        }

        return $productPriceApproval->getDataModel();
    }

    /**
     * @inheritDoc
     */
    public function getPending(): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', ProductPriceApprovalInterface::STATUS_PENDING);
        return $collection->getItems();
    }
}
