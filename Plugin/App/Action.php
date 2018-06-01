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
namespace KiwiCommerce\AdminActivity\Plugin\App;

/**
 * Class Action
 * @package KiwiCommerce\AdminActivity\Plugin\App
 */
class Action
{
    /**
     * @var \KiwiCommerce\AdminActivity\Model\Processor
     */
    public $processor;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * Action constructor.
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        \KiwiCommerce\AdminActivity\Model\Processor $processor,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->processor = $processor;
        $this->benchmark = $benchmark;
    }

    /**
     * Get before dispatch data
     * @param \Magento\Framework\Interception\InterceptorInterface $controller
     * @return void
     */
    public function beforeDispatch(\Magento\Framework\Interception\InterceptorInterface $controller)
    {
        $this->benchmark->start(__METHOD__);
        $actionName = $controller->getRequest()->getActionName();
        $fullActionName = $controller->getRequest()->getFullActionName();

        $this->processor->init($fullActionName, $actionName);
        $this->processor->addPageVisitLog($controller->getRequest()->getModuleName());
        $this->benchmark->end(__METHOD__);
    }
}
