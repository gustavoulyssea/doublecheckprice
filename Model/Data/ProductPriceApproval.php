<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model\Data;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * @noinspection PhpSuperClassIncompatibleWithInterfaceInspection
 */
class ProductPriceApproval extends AbstractExtensibleModel implements ProductPriceApprovalInterface
{
    /**
     * @inheriDoc
     */
    public function getEntityId(): mixed
    {
        return $this->_getData(self::ENTITY_ID);
    }

    /**
     * @inheriDoc
     */
    public function setEntityId($entityId): ProductPriceApprovalInterface
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheriDoc
     */
    public function getUsername(): ?string
    {
        return $this->getData(self::USERNAME);
    }

    /**
     * @inheriDoc
     */
    public function setUsername(string $username): ProductPriceApprovalInterface
    {
        return $this->setData(self::USERNAME, $username);
    }

    /**
     * @inheriDoc
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU);
    }

    /**
     * @inheriDoc
     */
    public function setSku(string $sku): ProductPriceApprovalInterface
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * @inheriDoc
     */
    public function getAttribute(): ?string
    {
        return $this->getData(self::ATTRIBUTE);
    }

    /**
     * @inheriDoc
     */
    public function setAttribute(string $attribute): ProductPriceApprovalInterface
    {
        return $this->setData(self::ATTRIBUTE, $attribute);
    }

    /**
     * @inheriDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheriDoc
     */
    public function setCreatedAt(string $createdAt): ProductPriceApprovalInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheriDoc
     */
    public function getOldPrice(): ?float
    {
        return (float)$this->getData(self::OLD_PRICE);
    }

    /**
     * @inheriDoc
     */
    public function setOldPrice(float $oldPrice): ProductPriceApprovalInterface
    {
        return $this->setData(self::OLD_PRICE, $oldPrice);
    }

    /**
     * @inheriDoc
     */
    public function getNewPrice(): ?float
    {
        return (float)$this->getData(self::NEW_PRICE);
    }

    /**
     * @inheriDoc
     */
    public function setNewPrice(float $newPrice): ProductPriceApprovalInterface
    {
        return $this->setData(self::NEW_PRICE, $newPrice);
    }

    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS);
    }

    public function setStatus(int $status): ProductPriceApprovalInterface
    {
        return $this->setData(self::STATUS, $status);
    }
}
