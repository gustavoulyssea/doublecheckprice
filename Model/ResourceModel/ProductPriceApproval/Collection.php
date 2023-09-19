<?php


declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use GumNet\DoubleCheckPrice\Model\ProductPriceApproval;
use GumNet\DoubleCheckPrice\Model\ResourceModel\ProductPriceApproval as ProductPriceApprovalResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @codeCoverageIgnore
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            ProductPriceApproval::class,
            ProductPriceApprovalResource::class
        );
    }

    public function load($printQuery = false, $logQuery = false): Collection
    {
        $this->addFieldToFilter(
            ProductPriceApprovalInterface::STATUS,
            ProductPriceApprovalInterface::STATUS_PENDING
        );
        return parent::load($printQuery = false, $logQuery = false);
    }

    public function count(): ?int
    {
        $this->addFieldToFilter(
            ProductPriceApprovalInterface::STATUS,
            ProductPriceApprovalInterface::STATUS_PENDING
        );
        return parent::count();
    }

}
