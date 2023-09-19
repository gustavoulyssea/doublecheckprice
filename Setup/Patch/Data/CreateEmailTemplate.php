<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Setup\Patch\Data;

use Magento\Email\Model\BackendTemplate;
use Magento\Email\Model\BackendTemplateFactory;
use Magento\Email\Model\ResourceModel\Template;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class CreateEmailTemplate implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BackendTemplateFactory $backendTemplateFactory
     * @param Template $templateResource
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly BackendTemplateFactory $backendTemplateFactory,
        private readonly Template $templateResource
    ) {
    }

    /**
     * Patch apply method
     *
     * @return void
     * @throws AlreadyExistsException
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        /** @var BackendTemplate $template */
        $template = $this->backendTemplateFactory->create();
        $templateTxt = <<<TXT
<table class="message-details">
    <tr>
        <td><strong>{{trans "Username"}}</strong></td>
        <td>{{var username}}</td>
    </tr>
    <tr>
        <td><strong>{{trans "SKU"}}</strong></td>
        <td>{{var sku}}</td>
    </tr>
    <tr>
        <td><strong>{{trans "Date"}}</strong></td>
        <td>{{var date}}</td>
    </tr>
    <tr>
        <td><strong>{{trans "Old Price"}}</strong></td>
        <td>{{var old_price}}</td>
    </tr>
    <tr>
        <td><strong>{{trans "New Price"}}</strong></td>
        <td>{{var new_price}}</td>
    </tr>
</table>
TXT;

        $template->setTemplateSubject(
            '{{trans "Product price approval"}}'
        )->setTemplateCode(
            'product_price_approval'
        )->setTemplateText(
            $templateTxt
        )->setTemplateStyles(
            ''
        )->setModifiedAt(
            date('Y-m-d H:i:s')
        )->setOrigTemplateCode(
            'contact_email_email_template'
        )->setOrigTemplateVariables(
            ''
        );
        $template->setTemplateType(2);

        $this->templateResource->save($template);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function revert(): void
    {
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
