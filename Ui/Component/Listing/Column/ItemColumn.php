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
 * Class ItemColumn
 * @package KiwiCommerce\AdminActivity\Ui\Component\Listing\Column
 */
class ItemColumn extends Column
{
    /**
     * @var int
     */
    const URL_COUNT = 7;

    /**
     * @var array
     */
    public $allowedAttributes = [
        'href',
        'title',
        'id',
        'class',
        'style',
        'target'
    ];

    /**
     * Escaper
     * @var \Magento\Framework\Escaper
     */
    public $escaper;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    public $backendUrl;

    /**
     * Filter manager
     * @var \Magento\Framework\Filter\FilterManager
     */
    public $filterManager;

    /**
     * ItemColumn constructor.
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\Context $contexts,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\Escaper $escaper,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $components,
        array $data
    ) {
        $this->escaper = $escaper;
        $this->backendUrl = $backendUrl;
        $this->filterManager = $contexts->getFilterManager();
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Escape HTML entities
     * @param string|array $data
     * @param array|null $allowedTags
     * @return string
     */
    public function escapeHtml($data, $allowedTags = null)
    {
        return $this->escaper->escapeHtml($data, $allowedTags);
    }

    /**
     * Render block HTML
     * @return string
     */
    public function _toHtml()
    {
        $length = 30;
        $itemName = $this->filterManager->truncate(
            $this->getLabel(),
            ['length' => $length, 'etc' => '...', 'remainder' => '', 'breakWords' => false]);
        return '<a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($itemName) . '</a></li>';
    }

    /**
     * Prepare link attributes as serialized and formatted string
     * @return string
     */
    public function getLinkAttributes()
    {
        $attributes = [];
        foreach ($this->allowedAttributes as $attribute) {
            $value = $this->getDataUsingMethod($attribute);
            if ($value !== null) {
                $attributes[$attribute] = $this->escapeHtml($value);
            }
        }

        if (!empty($attributes)) {
            return $this->serialize($attributes);
        }

        return '';
    }

    /**
     * Serialize attributes
     * @param   array $attributes
     * @param   string $valueSeparator
     * @param   string $fieldSeparator
     * @param   string $quote
     * @return  string
     */
    public function serialize($attributes = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $data = [];
        foreach ($attributes as $key => $value) {
            $data[] = $key . $valueSeparator . $quote . $value . $quote;
        }
        return implode($fieldSeparator, $data);
    }

    /**
     * Convert action to url
     * @param $url
     * @return string
     */
    public function prepareUrl($url)
    {
        if (current(explode('/', $url))=='theme' && count(explode('/', $url))==self::URL_COUNT) {
            list($module, $controller, $action, $scope, $store, $field, $id) = explode('/', $url);
            $editUrl = $this->backendUrl->getUrl(
                implode('/', [$module, $controller, $action, $scope, $store]),
                [$field => $id]
            );
            return $editUrl;
        }
        list($module, $controller, $action, $field, $id) = explode('/', $url);

        $editUrl = $this->backendUrl->getUrl(
            implode('/', [$module, $controller, $action]),
            [$field => $id]
        );

        return $editUrl;
    }

    /**
     * Initialize parameter for link
     * @return void
     */
    public function __initLinkParams($item)
    {
        $this->setHref($this->prepareUrl($item['item_url']));
        $this->setTitle($item['item_name']);
        $this->setTarget('_blank');
        $this->setLabel($item['item_name']);
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
                if (!empty($item['item_url'])) {
                    $this->__initLinkParams($item);
                    $item[$this->getData('name')] = $this->_toHtml();
                }
            }
        }

        return $dataSource;
    }
}
