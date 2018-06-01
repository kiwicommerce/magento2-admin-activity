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
namespace KiwiCommerce\AdminActivity\Test\Unit\Plugin;

/**
 * Class AuthTest
 * @package KiwiCommerce\AdminActivity\Test\Unit\Plugin
 */
class AuthTest extends \PHPUnit\Framework\TestCase
{
    public $authMock;

    public $authStorageMock;

    public $helperMock;

    public $loginRepositoryMock;

    public $user;

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->authMock = $this->getMockBuilder(\Magento\Backend\Model\Auth::class)
            ->setMethods(['getAuthStorage'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->authStorageMock = $this->getMockBuilder(\Magento\Backend\Model\Auth\StorageInterface::class)
            ->setMethods([
                'getUser',
                'processLogin',
                'isLoggedIn',
                'prolong',
                'processLogout'

            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperMock = $this->getMockBuilder(\KiwiCommerce\AdminActivity\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loginRepositoryMock = $this->getMockBuilder(\KiwiCommerce\AdminActivity\Api\LoginRepositoryInterface
        ::class)
            ->setMethods(['setUser','addLog','getListBeforeDate'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->user = $this->getMockBuilder(\Magento\User\Model\User::class)
            ->setMethods(['addLogoutLog'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->authTest = $objectManager->getObject(
            \KiwiCommerce\AdminActivity\Plugin\Auth::class,
            [
                'helper' => $this->helperMock,
                'loginRepository' => $this->loginRepositoryMock

            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testAroundLogout()
    {
        $this->authMock
            ->expects($this->once())
            ->method('getAuthStorage')
            ->willReturn($this->authStorageMock);

        $this->authStorageMock
            ->expects($this->once())
            ->method('getUser')
            ->willReturn('user');

        $this->helperMock
            ->expects($this->once())
            ->method('isLoginEnable')
            ->willReturn($this->loginRepositoryMock);

        $this->loginRepositoryMock
            ->expects($this->once())
            ->method('setUser')
            ->with('user')
            ->willReturn($this->user);

        $this->user
            ->expects($this->once())
            ->method('addLogoutLog')
            ->willReturn('LogoutLog');

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock->expects($this->once())->method('__invoke');

        $this->authTest->aroundLogout($this->authMock, $callbackMock);
    }

    /**
     * @requires PHP 7.0
     */
    public function testAroundLogoutIsLoginEnableFalse()
    {
        $this->helperMock
            ->expects($this->once())
            ->method('isLoginEnable')
            ->willReturn(false);

        $callbackMock = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $callbackMock
            ->expects($this->once())
            ->method('__invoke');
        $this->assertNull($this->authTest->aroundLogout($this->authMock, $callbackMock));
    }
}
