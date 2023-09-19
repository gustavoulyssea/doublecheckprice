<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Api;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ProductPriceApprovalRepositoryInterface
{
    /**
     * Persist ProductPriceApproval to database
     *
     * @param ProductPriceApprovalInterface $productPriceApproval
     * @throws LocalizedException
     * @return ProductPriceApprovalInterface
     */
    public function save(
        ProductPriceApprovalInterface $productPriceApproval
    ): ProductPriceApprovalInterface;

    /**
     * Get ProductPriceApproval through entity id
     *
     * @param int $entityId
     * @throws NoSuchEntityException
     * @return ProductPriceApprovalInterface
     */
    public function get(int $entityId): ProductPriceApprovalInterface;


    /**
     * Get pending entries
     * @return ProductPriceApprovalInterface[]
     */
    public function getPending(): array;
}
