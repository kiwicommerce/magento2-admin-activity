<?php
/**
 * KiwiCommerce
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please contact us https://kiwicommerce.co.uk/contacts.
 *
 * @category   KiwiCommerce
 * @package    KiwiCommerce_AdminActivity
 * @copyright  Copyright (C) 2018 Kiwi Commerce Ltd (https://kiwicommerce.co.uk/)
 * @license    https://kiwicommerce.co.uk/magento2-extension-license/
 */
namespace KiwiCommerce\AdminActivity\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use \KiwiCommerce\AdminActivity\Helper\Data as Helper;

/**
 * Class ActionTypeColumn
 * @package KiwiCommerce\AdminActivity\Ui\Component\Listing\Column
 */
class ActionTypeColumn extends Column
{
    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Data
     */
    public $helper;

    /**
     * ActionTypeColumn constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Helper $helper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Helper $helper,
        array $components = [],
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['action_type'])) {
                    $item['action_type'] = $this->helper->getActionTranslatedLabel($item['action_type']);
                }
            }
        }
        return $dataSource;
    }
}
