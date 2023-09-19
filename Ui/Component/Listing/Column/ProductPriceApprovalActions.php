<?php

declare(strict_types=1);

namespace GumNet\DoubleCheckPrice\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ProductPriceApprovalActions extends Column
{
    public const URL_PATH_ACCEPT = 'product_price_approval/index/accept';
    public const URL_PATH_REJECT = 'product_price_approval/index/reject';

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        private readonly UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'accept' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_ACCEPT,
                                [
                                    'id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Accept')
                        ],
                        'reject' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_REJECT,
                                [
                                    'id' => $item['entity_id']
                                ]
                            ),
                            'label' => __('Reject')
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
