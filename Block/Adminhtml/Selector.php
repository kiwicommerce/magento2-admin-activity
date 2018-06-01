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
namespace KiwiCommerce\AdminActivity\Block\Adminhtml;

/**
 * Class Selector
 * @package KiwiCommerce\AdminActivity\Block\Adminhtml
 */
class Selector extends \Magento\Backend\Block\Template
{
    /**
     * Revert Activity Log action URL
     * @return string
     */
    public function getRevertUrl()
    {
        return $this->getUrl('adminactivity/activity/revert');
    }
}
