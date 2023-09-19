<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterfaceFactory;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval\Collection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

/**
 * @codeCoverageIgnore
 */
class ProductPriceApproval extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'product_price_approval';

    protected $_cacheTag = 'product_price_approval';

    protected $_eventPrefix = 'product_price_approval';

    /**
     * ProductPriceApproval constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ProductPriceApprovalInterfaceFactory $productPriceApprovalFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param ResourceModel\ProductPriceApproval $resource
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        private readonly ProductPriceApprovalInterfaceFactory $productPriceApprovalFactory,
        private readonly DataObjectHelper $dataObjectHelper,
        ResourceModel\ProductPriceApproval $resource,
        Collection $resourceCollection,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_init(ResourceModel\ProductPriceApproval::class);
    }

    /**
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Return the Data Model
     *
     * @return ProductPriceApprovalInterface
     */
    public function getDataModel(): ProductPriceApprovalInterface
    {
        $dataModel = $this->productPriceApprovalFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $dataModel,
            $this->getData(),
            ProductPriceApprovalInterface::class
        );

        return $dataModel;
    }
}
