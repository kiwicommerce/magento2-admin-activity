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
use KiwiCommerce\AdminActivity\Api\ActivityRepositoryInterface;

/**
 * Class SaveBefore
 * @package KiwiCommerce\AdminActivity\Observer
 */
class SaveBefore implements ObserverInterface
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Processor
     */
    public $processor;

    /**
     * @var ActivityRepositoryInterface
     */
    public $activityRepository;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * SaveBefore constructor.
     * @param Helper $helper
     * @param \KiwiCommerce\AdminActivity\Model\Processor $processor
     * @param ActivityRepositoryInterface $activityRepository
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $banchmark
     */
    public function __construct(
        Helper $helper,
        \KiwiCommerce\AdminActivity\Model\Processor $processor,
        ActivityRepositoryInterface $activityRepository,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->processor = $processor;
        $this->activityRepository = $activityRepository;
        $this->benchmark = $benchmark;
    }

    /**
     * Save before
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
        if ($object->getId() == 0) {
            $object->setCheckIfIsNew(true);
        } else {
            $object->setCheckIfIsNew(false);
            if ($this->processor->validate($object)) {
                $origData = $object->getOrigData();
                if (!empty($origData)) {
                    return $observer;
                }
                $data = $this->activityRepository->getOldData($object);
                foreach ($data->getData() as $key => $value) {
                    $object->setOrigData($key, $value);
                }
            }
        }
        $this->benchmark->end(__METHOD__);
        return $observer;
    }
}
