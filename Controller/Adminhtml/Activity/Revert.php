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
namespace KiwiCommerce\AdminActivity\Controller\Adminhtml\Activity;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

/**
 * Class Revert
 * @package KiwiCommerce\AdminActivity\Controller\Adminhtml\Activity
 */
class Revert extends Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Processor
     */
    public $processor;

    /**
     * Revert constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \KiwiCommerce\AdminActivity\Model\Processor $processor
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->processor = $processor;
    }

    /**
     * Revert action
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $activityId = $this->getRequest()->getParam('id');
        $result = $this->processor->revertActivity($activityId);
        return $this->resultJsonFactory->create()->setData($result);
    }
}
