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
namespace KiwiCommerce\AdminActivity\Test\Unit\Plugin\User;

/**
 * Class DeleteTest
 * @package KiwiCommerce\AdminActivity\Test\Unit\Plugin
 */
class DeleteTest extends \PHPUnit\Framework\TestCase
{
    public $delete;

    public $userMock;

    public $userResourceMock;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->userResourceMock = $this->getMockBuilder(\Magento\User\Model\ResourceModel\User::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $this->userMock = $this->getMockBuilder(\Magento\User\Model\User::class)
            ->setMethods(['load', 'getId', 'afterDelete'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->delete = $objectManager->getObject(
            \KiwiCommerce\AdminActivity\Plugin\User\Delete::class,
            []
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testaroundDelete()
    {

        $this->userMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->userMock->expects($this->once())
            ->method('load')
            ->willReturn($this->userMock);

        $this->userMock->expects($this->once())
            ->method('afterDelete')
            ->willReturnSelf();

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock
            ->expects($this->once())
            ->method('__invoke');

        $this->delete->aroundDelete($this->userResourceMock, $callbackMock, $this->userMock);
    }
}
