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
 * Class LoginSuccess
 * @package KiwiCommerce\AdminActivity\Observer
 */
class LoginSuccess implements ObserverInterface
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface
     */
    public $loginRepository;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * LoginSuccess constructor.
     * @param Helper $helper
     * @param \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface $loginRepository
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        Helper $helper,
        \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface $loginRepository,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->loginRepository = $loginRepository;
        $this->benchmark = $benchmark;
    }
    
    /**
     * Login success
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isLoginEnable()) {
            return $observer;
        }
        
        $this->loginRepository
            ->setUser($observer->getUser())
            ->addSuccessLog();
        $this->benchmark->end(__METHOD__);
    }
}
