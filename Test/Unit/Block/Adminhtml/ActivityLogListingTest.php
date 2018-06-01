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
namespace KiwiCommerce\AdminActivity\Test\Unit\Block\Adminhtml;

/**
 * Class ActivityLogListingTest
 * @package KiwiCommerce\AdminActivity\Test\Unit\Block\Adminhtml
 */
class ActivityLogListingTest extends \PHPUnit\Framework\TestCase
{
    public $activityRepositoryMock;

    public $browserMock;

    public $contextMock;

    public $request;

    public $activityLogListing;

    public $activityModel;

    public $userAgent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) G...';

    public $logData = [
        'username' => 'admin',
        'module' => 'Admin',
        'name' => 'admin admin',
        'fullaction' => 'Adminhtml/Index/Index',
        'browser' => '',
        'date' => '2018-05-18 14:51:23'
    ];

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->activityRepositoryMock = $this->getMockForAbstractClass(
            \KiwiCommerce\AdminActivity\Api\ActivityRepositoryInterface::class,
            [],
            '',
            false
        );

        $this->browserMock = $this->createMock(\KiwiCommerce\AdminActivity\Helper\Browser::class);

        $this->request = $this->getMockForAbstractClass(
            \Magento\Framework\App\RequestInterface::class,
            [],
            '',
            false
        );

        $this->contextMock = $objectManager->getObject(
            \Magento\Backend\Block\Template\Context::class,
            [
                'request' => $this->request
            ]
        );

        $this->activityModel = $this
            ->getMockBuilder(\KiwiCommerce\AdminActivity\Model\Activity::class)
            ->setMethods(
                [
                    'getUserAgent',
                    'getUsername',
                    'getModule',
                    'getName',
                    'getFullaction',
                    'getUpdatedAt'
                ]
            )
            ->disableOriginalConstructor()
            ->getMock();

        $this->activityLogListing = $objectManager->getObject(
            \KiwiCommerce\AdminActivity\Block\Adminhtml\ActivityLogListing::class,
            [
                'activityRepository' => $this->activityRepositoryMock,
                'browser' => $this->browserMock,
                'context' => $this->contextMock,
            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testGetLogListing()
    {
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(1);

        $this->activityRepositoryMock->expects($this->once())
            ->method('getActivityLog')
            ->with(1)
            ->willReturn($this->activityModel);

        $this->activityLogListing->getLogListing();
    }

    /**
     * @requires PHP 7.0
     */
    public function testGetAdminDetails()
    {
        $this->request->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn(1);

        $this->activityRepositoryMock->expects($this->once())
            ->method('getActivityById')
            ->with(1)
            ->willReturn($this->activityModel);

        $this->browserMock->expects($this->once())
            ->method('reset')
            ->willReturnSelf();

        $this->activityModel->expects($this->once())
            ->method('getUserAgent')
            ->willReturn($this->userAgent);

        $this->browserMock->expects($this->once())
            ->method('setUserAgent')
            ->with($this->userAgent)
            ->willReturnSelf();

        $this->activityModel->expects($this->once())
            ->method('getUsername')
            ->willReturn($this->logData['username']);

        $this->activityModel->expects($this->once())
            ->method('getModule')
            ->willReturn($this->logData['module']);

        $this->activityModel->expects($this->once())
            ->method('getName')
            ->willReturn($this->logData['name']);

        $this->activityModel->expects($this->once())
            ->method('getFullaction')
            ->willReturn($this->logData['fullaction']);

        $this->activityModel->expects($this->once())
            ->method('getUpdatedAt')
            ->willReturn($this->logData['date']);

        $this->assertEquals($this->logData, $this->activityLogListing->getAdminDetails());
    }
}