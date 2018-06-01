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
 * Class StatusColumn
 * @package KiwiCommerce\AdminActivity\Ui\Component\Listing\Column
 */
class RevertStatusColumn extends Column
{
    /**
     * Prepare Data Source
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['is_revertable']==\KiwiCommerce\AdminActivity\Model\Activity\Status::ACTIVITY_REVERTABLE) {
                    $item[$this->getData('name')] =
                        '<span class="grid-severity-minor" title=""><span>Revert</span></span>';
                } elseif ($item['is_revertable']==
                    \KiwiCommerce\AdminActivity\Model\Activity\Status::ACTIVITY_REVERT_SUCCESS) {
                    $item[$this->getData('name')] =
                        '<span class="grid-severity-notice" title=""><span>Success</span></span>'.
                        '<br/><strong>Reverted By:</strong> '.$item['revert_by'];
                } elseif ($item['is_revertable']==\KiwiCommerce\AdminActivity\Model\Activity\Status::ACTIVITY_FAIL) {
                    $item[$this->getData('name')] =
                        '<span class="grid-severity-critical" title=""><span>Faild</span></span>';
                } else {
                    $item[$this->getData('name')] = '-';
                }
            }
        }

        return $dataSource;
    }
}
