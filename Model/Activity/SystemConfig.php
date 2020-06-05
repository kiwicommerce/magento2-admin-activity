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
namespace KiwiCommerce\AdminActivity\Model\Activity;

/**
 * Class SystemConfig
 * @package KiwiCommerce\AdminActivity\Model\Activity
 */
class SystemConfig implements \KiwiCommerce\AdminActivity\Api\Activity\ModelInterface
{
    /**
     * @var \Magento\Framework\DataObject
     */
    public $dataObject;

    /**
     * @var \Magento\Framework\App\Config\ValueFactory
     */
    public $valueFactory;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    public $configWriter;

    /**
     * SystemConfig constructor.
     * @param \Magento\Framework\DataObject $dataObject
     * @param \Magento\Framework\App\Config\ValueFactory $valueFactory
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     */
    public function __construct(
        \Magento\Framework\DataObject $dataObject,
        \Magento\Framework\App\Config\ValueFactory $valueFactory,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->dataObject = $dataObject;
        $this->valueFactory = $valueFactory;
        $this->configWriter = $configWriter;
    }

    /**
     * Get config path
     * @param $model
     * @return string
     */
    public function getPath($model)
    {
        if ($model->getData('path')) {
            return current(
                explode(
                    '/',
                    $model->getData('path')
                )
            );
        }

        return '';
    }

    /**
     * Get old activity data of system config module
     * @param $model
     * @return mixed
     */
    public function getOldData($model)
    {
        $path = $this->getPath($model);
        $systemData = $this->valueFactory->create()->getCollection()->addFieldToFilter('path', ['like'=> $path.'/%']);
        $data = [];
        foreach ($systemData->getData() as $config) {
            $splittedPath = explode('/', $config['path']);
            if (count($splittedPath) === 2) {
                [$group, $field] = explode('/', $config['path']);
            } else {
                [$path, $group, $field] = explode('/', $config['path']);
            }

            $data[$group]['fields'][$field]['value'] = $config['value'];
        }

        return $this->dataObject->setData($data);
    }

    /**
     * Get edit activity data of system config module
     * @param $model
     * @return mixed
     */
    public function getEditData($model, $fieldArray)
    {
        $logData = [];

        $path = $this->getPath($model);
        $result = $this->collectAdditionalData($model->getOrigData(), $model->getGroups());

        $model->setConfig('System Configuration');
        $model->setId($path);

        if (!empty($result) && is_array($result)) {
            foreach ($result as $group => $fields) {
                if (!empty($fields['fields'])) {
                    foreach ($fields['fields'] as $field => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        $fieldPath = implode('/', [
                            $path, $group, $field
                        ]);

                        $logData[$fieldPath] = [
                            'old_value' => $value['old_value'],
                            'new_value' => $value['new_value']
                        ];
                    }
                }
            }
        }

        return $logData;
    }

    /**
     * Get revert activity data of system config module
     * @param $logData
     * @param $scopeId
     * @return bool
     */
    public function revertData($logData, $scopeId)
    {
        if (!empty($logData)) {
            foreach ($logData as $log) {
                $this->configWriter->save(
                    $log->getFieldName(),
                    $log->getOldValue(),
                    \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                    $scopeId
                );
            }
        }
        return true;
    }

    /**
     * Set additional data
     * @param $oldData
     * @param $newData
     * @return array
     */
    public function collectAdditionalData($oldData, $newData)
    {
        $result = [];
        if (!empty($oldData) && is_array($oldData)) {
            foreach ($oldData as $key => $value) {
                if (isset($newData[$key])) {
                    if (is_array($value)) {
                        $result[$key] = $this->collectAdditionalData($value, $newData[$key]);
                    } elseif (!is_array($newData[$key]) && (string)$value != (string)$newData[$key]) {
                        $result['new_value'] = (string)$newData[$key];
                        $result['old_value'] = $value;
                    }
                }
            }
        }
        return $result;
    }
}
