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
namespace KiwiCommerce\AdminActivity\Test\Unit\Observer;

class SaveAfterTest extends \PHPUnit\Framework\TestCase
{
    public $saveAfter;

    public $processorMock;

    public $helperMock;

    public $observerMock;

    public $eventMock;

    public $objectMock;

    public $configMock;

    public $eventConfig = ['action' => 'massCancel', 'module' => 'catalog_products'];

    /**
     * @requires PHP 7.0
     */
    public function setUp()
    {
        $this->processorMock = $this->getMockBuilder(\KiwiCommerce\AdminActivity\Model\Processor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helperMock = $this->getMockBuilder(\KiwiCommerce\AdminActivity\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->observerMock = $this
            ->getMockBuilder(\Magento\Framework\Event\Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this
            ->getMockBuilder(\Magento\Framework\Event::class)
            ->setMethods(['getObject'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectMock = $this
            ->getMockBuilder(\Magento\Framework\DataObject::class)
            ->setMethods(['getCheckIfIsNew'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(\KiwiCommerce\AdminActivity\Model\Config::class)
            ->setMethods(['getEventByAction','getTrackFieldModel','getEventModel','getActivityModuleConstant'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->saveAfter = $objectManager->getObject(
            \KiwiCommerce\AdminActivity\Observer\SaveAfter::class,
            [
                'processor' => $this->processorMock,
                'helper' => $this->helperMock,
            ]
        );
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecute()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(true);

        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->eventMock);

        $this->eventMock
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($this->objectMock);

        $this->objectMock
            ->expects($this->atLeastOnce())
            ->method('getCheckIfIsNew')
            ->willReturn(true);

        $this->processorMock->expects($this->once())
            ->method('modelAddAfter')
            ->with($this->objectMock)
            ->willReturnSelf();

        $this->assertTrue($this->saveAfter->execute($this->observerMock));
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecuteIsEnableReturnFalse()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(false);

        $this->assertEquals($this->observerMock, $this->saveAfter->execute($this->observerMock));
    }

    /**
     * @requires PHP 7.0
     */
    public function testExecuteGetCheckIfIsNewReturnFalse()
    {
        $this->helperMock->expects($this->once())
            ->method('isEnable')
            ->willReturn(true);

        $this->observerMock
            ->expects($this->atLeastOnce())
            ->method('getEvent')
            ->willReturn($this->eventMock);
        $this->eventMock
            ->expects($this->atLeastOnce())
            ->method('getObject')
            ->willReturn($this->objectMock);

        $this->objectMock
            ->expects($this->atLeastOnce())
            ->method('getCheckIfIsNew')
            ->willReturn(false);

        $this->processorMock->expects($this->once())
            ->method('validate')
            ->with($this->objectMock)
            ->willReturn(true);

        $this->processorMock->expects($this->once())
            ->method('modelEditAfter')
            ->with($this->objectMock)
            ->willReturnSelf();

        $this->assertTrue($this->saveAfter->execute($this->observerMock));
    }
}
