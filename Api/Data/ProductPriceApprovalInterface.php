<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Api\Data;

interface ProductPriceApprovalInterface
{
    public const ENTITY_ID = 'entity_id';
    public const USERNAME = 'username';
    public const SKU = 'sku';
    public const ATTRIBUTE = 'attribute';
    public const CREATED_AT = 'created_at';
    public const OLD_PRICE = 'old_price';
    public const NEW_PRICE = 'new_price';
    public const STATUS = 'status';
    public const STATUS_PENDING = 0;
    public const STATUS_ACCEPTED = 1;
    public const STATUS_REJECTED = -1;

    /**
     * Get entity ID
     *
     * @return mixed
     */
    public function getEntityId();

    /**
     * Set entity ID
     *
     * @param int $entityId
     * @return ProductPriceApprovalInterface
     */
    public function setEntityId($entityId): ProductPriceApprovalInterface;


    /**
     * Get username
     *
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * Set username
     *
     * @param string $username
     * @return ProductPriceApprovalInterface
     */
    public function setUsername(string $username): ProductPriceApprovalInterface;

    /**
     * Get SKU
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Set SKU
     *
     * @param string $sku
     * @return ProductPriceApprovalInterface
     */
    public function setSku(string $sku): ProductPriceApprovalInterface;

    /**
     * Get attribute
     *
     * @return string|null
     */
    public function getAttribute(): ?string;

    /**
     * Set attribute
     *
     * @param string $attribute
     * @return ProductPriceApprovalInterface
     */
    public function setAttribute(string $attribute): ProductPriceApprovalInterface;

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return ProductPriceApprovalInterface
     */
    public function setCreatedAt(string $createdAt): ProductPriceApprovalInterface;

    /**
     * Get old price
     *
     * @return float|null
     */
    public function getOldPrice(): ?float;

    /**
     * Set old price
     *
     * @param float $oldPrice
     * @return ProductPriceApprovalInterface
     */
    public function setOldPrice(float $oldPrice): ProductPriceApprovalInterface;

    /**
     * Get new price
     *
     * @return float|null
     */
    public function getNewPrice(): ?float;

    /**
     * Set new price
     *
     * @param float $newPrice
     * @return ProductPriceApprovalInterface
     */
    public function setNewPrice(float $newPrice): ProductPriceApprovalInterface;

    /**
     * Get status
     *
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * Set status
     *
     * @param int $status
     * @return ProductPriceApprovalInterface
     */
    public function setStatus(int $status): ProductPriceApprovalInterface;
}
