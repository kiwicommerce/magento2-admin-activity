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

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class UserAgentColumn
 * @package KiwiCommerce\AdminActivity\Ui\Component\Listing\Column
 */
class UserAgentColumn extends Column
{
    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Browser
     */
    public $browser;

    /**
     * UserAgentColumn constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \KiwiCommerce\AdminActivity\Helper\Browser $browser
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \KiwiCommerce\AdminActivity\Helper\Browser $browser,
        array $components,
        array $data
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->browser = $browser;
    }

    /**
     * Get user agent data
     * @param $item
     * @return string
     */
    public function getAgent($item)
    {
        $this->browser->reset();
        $this->browser->setUserAgent($item['user_agent']);
        return $this->browser->__toString();
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
                $item[$this->getData('name')] = $this->getAgent($item);
            }
        }

        return $dataSource;
    }
}
