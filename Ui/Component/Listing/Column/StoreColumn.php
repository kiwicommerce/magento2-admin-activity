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

/**
 * Class StoreColumn
 * @package KiwiCommerce\AdminActivity\Ui\Component\Listing\Column
 */
class StoreColumn extends \Magento\Store\Ui\Component\Listing\Column\Store
{
    /**
     * Field name for store
     * @var string
     */
    const KEY_FIELD = 'store_id';

    /**
     * Prepare Item
     * @param array $item
     * @return string
     */
    public function prepareItem(array $item)
    {
        //TODO: To set and display default value
        $this->storeKey = !empty($this->storeKey)?$this->storeKey:self::KEY_FIELD;
        if ($item[$this->storeKey]==0) {
            $origStores['0'] = 0;
        }

        $content = '';
        if (!empty($item[$this->storeKey])) {
            $origStores = $item[$this->storeKey];
        }

        if (empty($origStores)) {
            return '';
        }
        if (!is_array($origStores)) {
            $origStores = [$origStores];
        }
        if (in_array(0, $origStores) && count($origStores) == 1) {
            return __('All Store Views');
        }

        $data = $this->systemStore->getStoresStructure(false, $origStores);

        foreach ($data as $website) {
            $content .= $website['label'] . "<br/>";
            foreach ($website['children'] as $group) {
                $content .= str_repeat('&nbsp;', 3) . $this->escaper->escapeHtml($group['label']) . "<br/>";
                foreach ($group['children'] as $store) {
                    $content .= str_repeat('&nbsp;', 6) . $this->escaper->escapeHtml($store['label']) . "<br/>";
                }
            }
        }

        return $content;
    }
}
