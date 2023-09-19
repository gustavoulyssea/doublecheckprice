<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Model;

use GumNet\DoubleCheckPrice\Api\Data\ProductPriceApprovalInterface;
use Magento\Email\Model\ResourceModel\Template\Collection;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class EmailSender
{
    public const CONFIG_ENABLE = 'catalog/price_approval/email_enable';
    public const CONFIG_SENDER_NAME = 'general/store_information/name';
    public const CONFIG_SENDER_EMAIL = 'trans_email/ident_general/email';
    public const CONFIG_TO_EMAIL = 'catalog/price_approval/email_to';
    public const EMAIL_TEMPLATE = 'product_price_approval';
    public const TEMPLATE_CODE_FIELD = 'template_code';

    /**
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     * @param CollectionFactory $templateCollectionFactory
     */
    public function __construct(
        private readonly TransportBuilder $transportBuilder,
        private readonly StoreManagerInterface $storeManager,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly LoggerInterface $logger,
        private readonly CollectionFactory $templateCollectionFactory
    ) {
    }

    /**
     * Send price approval notification email
     *
     * @param ProductPriceApprovalInterface $priceApproval
     * @return void
     */
    public function send(ProductPriceApprovalInterface $priceApproval): void
    {
        if (!$this->scopeConfig->getValue(self::CONFIG_ENABLE, ScopeInterface::SCOPE_STORE)) {
            return;
        }
        try {
            $senderName = $this->scopeConfig->getValue(self::CONFIG_SENDER_NAME, ScopeInterface::SCOPE_STORE);
            $senderEmail = $this->scopeConfig->getValue(self::CONFIG_SENDER_EMAIL, ScopeInterface::SCOPE_STORE);
            if (!$toEmail = $this->scopeConfig->getValue(self::CONFIG_TO_EMAIL, ScopeInterface::SCOPE_STORE)) {
                return;
            }
            $toEmail = explode(",", $toEmail);
            $sender = [
                'name' => $senderName,
                'email' => $senderEmail,
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->getTemplateId())
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_ADMINHTML,
                        'store' => $this->storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars([
                    'username' => $priceApproval->getUsername(),
                    'sku'  => $priceApproval->getSku(),
                    'date'  => $priceApproval->getCreatedAt(),
                    'old_price' => $priceApproval->getOldPrice(),
                    'new_price' => $priceApproval->getNewPrice(),
                ])
                ->setFromByScope($sender)
                ->addTo($toEmail)
                ->getTransport();
            $transport->sendMessage();
        } catch (LocalizedException | MailException $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    private function getTemplateId(): int
    {
        /** @var Collection $collection */
        $collection = $this->templateCollectionFactory->create();
        $collection->addFieldToFilter(self::TEMPLATE_CODE_FIELD, self::EMAIL_TEMPLATE);
        return (int)$collection->getFirstItem()->getTemplateId();
    }
}
