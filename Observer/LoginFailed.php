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
 * Class LoginFailed
 * @package KiwiCommerce\AdminActivity\Observer
 */
class LoginFailed implements ObserverInterface
{
    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var \Magento\User\Model\User
     */
    public $user;

    /**
     * @var \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface
     */
    public $loginRepository;

    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * LoginFailed constructor.
     * @param Helper $helper
     * @param \Magento\User\Model\User $user
     * @param \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface $loginRepository
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        Helper $helper,
        \Magento\User\Model\User $user,
        \KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface $loginRepository,
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->helper = $helper;
        $this->user = $user;
        $this->loginRepository = $loginRepository;
        $this->benchmark = $benchmark;
    }

    /**
     * Login failed
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->benchmark->start(__METHOD__);
        if (!$this->helper->isLoginEnable()) {
            return $observer;
        }

        $user = null;
        if ($observer->getUserName()) {
            $user = $this->user->loadByUsername($observer->getUserName());
        }

        $this->loginRepository
            ->setUser($user)
            ->addFailedLog($observer->getException()->getMessage());
        $this->benchmark->end(__METHOD__);
    }
}
