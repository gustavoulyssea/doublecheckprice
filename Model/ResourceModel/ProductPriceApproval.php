<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @codeCoverageIgnore
 */
class ProductPriceApproval extends AbstractDb
{
    /**
     * {@inheritdoc}
     */
    protected $_useIsObjectNew = true;

    /**
     * {@inheritdoc}
     */
    protected $_isPkAutoIncrement = true;

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('product_price_approval', 'entity_id');
    }
}
