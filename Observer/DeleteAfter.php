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
use \KiwiCommerce\AdminActivity\Helper\Data as Helper;
use \KiwiCommerce\AdminActivity\Api\ActivityRepositoryInterface;

/**
 * Class DeleteAfter
 * @package KiwiCommerce\AdminActivity\Observer
 */
class DeleteAfter implements ObserverInterface
{
    /**
     * @var string
     */
    const SYSTEM_CONFIG = 'adminhtml_system_config_save';

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
     * DeleteAfter constructor.
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     * @param Helper $helper
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
        if ($this->processor->validate($object) && ($this->processor->initAction==self::SYSTEM_CONFIG)) {
            $this->processor->modelEditAfter($object);
        }
        $this->processor->modelDeleteAfter($object);
        $this->benchmark->end(__METHOD__);
    }
}
