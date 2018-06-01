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

/**
 * Class SaveAfter
 * @package KiwiCommerce\AdminActivity\Observer
 */
class SaveAfter implements ObserverInterface
{
    /**
     * @var string
     */
    const ACTION_MASSCANCEL = 'massCancel';

    /**
     * @var string
     */
    const SYSTEM_CONFIG = 'adminhtml_system_config_save';

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Processor
     */
    private $processor;

    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * SaveAfter constructor.
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     * @param Helper $helper
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        \KiwiCommerce\AdminActivity\Model\Processor $processor,
        Helper $helper,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->processor = $processor;
        $this->helper = $helper;
        $this->benchmark = $benchmark;
    }

    /**
     * Save after
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer|boolean
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->benchmark->start(__METHOD__);

        if (!$this->helper->isEnable()) {
            return $observer;
        }
        $object = $observer->getEvent()->getObject();
        if ($object->getCheckIfIsNew()) {
            if ($this->processor->initAction==self::SYSTEM_CONFIG) {
                $this->processor->modelEditAfter($object);
            }
            $this->processor->modelAddAfter($object);
        } else {
            if ($this->processor->validate($object)) {
                if ($this->processor->eventConfig['action']==self::ACTION_MASSCANCEL) {
                    $this->processor->modelDeleteAfter($object);
                }
                $this->processor->modelEditAfter($object);
            }
        }
        $this->benchmark->end(__METHOD__);
        return true;
    }
}
