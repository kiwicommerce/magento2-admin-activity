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
namespace KiwiCommerce\AdminActivity\Plugin\User;

/**
 * Class Delete
 * @package KiwiCommerce\AdminActivity\Plugin\User
 */
class Delete
{
    /**
     * @var \KiwiCommerce\AdminActivity\Helper\Benchmark
     */
    public $benchmark;

    /**
     * Delete constructor.
     * @param \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
     */
    public function __construct(
        \KiwiCommerce\AdminActivity\Helper\Benchmark $benchmark
    ) {
        $this->benchmark = $benchmark;
    }
    /**
     * @param \Magento\User\Model\ResourceModel\User $user
     * @param callable $proceed
     * @param $object
     * @return mixed
     */
    public function aroundDelete(\Magento\User\Model\ResourceModel\User $user, callable $proceed, $object)
    {
        $this->benchmark->start(__METHOD__);
        $object->load($object->getId());

        $result = $proceed($object);
        $object->afterDelete();

        $this->benchmark->end(__METHOD__);
        return $result;
    }
}
