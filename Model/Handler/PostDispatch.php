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
namespace KiwiCommerce\AdminActivity\Model\Handler;

/**
 * Class PostDispatch
 * @package KiwiCommerce\AdminActivity\Model\Handler
 */
class PostDispatch
{
    /**
     * Request
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    public $response;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    public $productRepository;

    /**
     * @var \Magento\Backend\Model\Session
     */
    public $session;

    /**
     * PostDispatch constructor.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Backend\Model\Session $session
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Backend\Model\Session $session
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    /**
     * @param $model
     * @return array
     */
    public function getProductAttributes($model)
    {
        $logData = [];
        $status = $this->request->getParam('status', '');
        if($status != '') {
            $logData['status'] = [
                'old_value' => $model->getStatus(),
                'new_value' => $status
            ];
        }

        $attributes = $this->request->getParam('attributes', []);
        if(!empty($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $logData[$attribute] = [
                    'old_value' => $model->getData($attribute),
                    'new_value' => $value
                ];
            }
        }

        $inventories = $this->request->getParam('inventory', []);
        if(!empty($inventories)) {
            foreach ($inventories as $field => $value) {
                $logData[$field] = [
                    'old_value' => $model->getData($field),
                    'new_value' => $value
                ];
            }
        }

        $websiteIds = $this->request->getParam('remove_website', []);
        if ($websiteIds) {
            $logData['remove_website_ids'] = [
                'old_value' => '[]',
                'new_value' => implode(', ', $websiteIds)
            ];
        }

        $websiteIds = $this->request->getParam('add_website', []);
        if ($websiteIds) {
            $logData['add_website_ids'] = [
                'old_value' => '[]',
                'new_value' => implode(', ', $websiteIds)
            ];
        }

        return $logData;
    }

    /**
     * Set product update activity log
     * @param $config
     * @param $processor
     */
    public function productUpdate($config, $processor)
    {
        $activity = $processor->_initLog();
        $activity->setIsRevertable(1);

        $selected = $this->request->getParam('selected');
        if(empty($selected)) {
            $selected = $this->session->getProductIds();
        }
        if(!empty($selected)) {
            foreach ($selected as $id) {

                $model = $this->productRepository->getById($id);

                $log = clone $activity;
                $log->setItemName($model->getData($processor->config->getActivityModuleItemField($config['module'])));
                $log->setItemUrl($processor->getEditUrl($model));

                $logData = $processor->handler->__initLog($this->getProductAttributes($model));
                $logDetail = $processor->_initActivityDetail($model);

                $processor->activityLogs[] = [
                    \KiwiCommerce\AdminActivity\Model\Activity::class => $log,
                    \KiwiCommerce\AdminActivity\Model\ActivityLog::class => $logData,
                    \KiwiCommerce\AdminActivity\Model\ActivityLogDetail::class => $logDetail
                ];
            }
        }
    }
}