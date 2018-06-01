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
namespace KiwiCommerce\AdminActivity\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class LoadAfter
 * @package KiwiCommerce\AdminActivity\Observer
 */
class LoadAfter implements ObserverInterface
{
    /**
     * @var \KiwiCommerce\AdminActivity\Model\Processor
     */
    private $processor;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Data
     */
    public $helper;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * LoadAfter constructor.
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     * @param \KiwiCommerce\AdminActivity\Helper\Data $helper
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        \KiwiCommerce\AdminActivity\Model\Processor $processor,
        \KiwiCommerce\AdminActivity\Helper\Data $helper,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->processor = $processor;
        $this->helper = $helper;
        $this->benchmark = $benchmark;
    }

    /**
     * Delete after
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isEnable()) {
            return $observer;
        }
        $object = $observer->getEvent()->getObject();
        $this->processor->modelLoadAfter($object);
        $this->benchmark->end(__METHOD__);
    }
}
