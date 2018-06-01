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
namespace KiwiCommerce\AdminActivity\Model\Config;

/**
 * Class Converter
 * @package KiwiCommerce\AdminActivity\Model\Config
 */
class Converter implements \Magento\Framework\Config\ConverterInterface
{
    /**
     * Convert actions in array from Xpath object
     * @param \DOMDocument $source
     * @return array
     */
    public function convert($source)
    {
        $result = ['config' => []];
        $xpath = new \DOMXPath($source);
        $result['config']['actions'] = $this->_getActions($xpath);

        $modules = $xpath->query('/config/modules/module');
        foreach ($modules as $module) {
            $moduleId = $module->attributes->getNamedItem('name')->nodeValue;
            $result['config'][$moduleId] = $this->_processModule($module, $moduleId);
        }

        return $result;
    }

    /**
     * Retrieves actions array from Xpath object
     * @param \DOMXPath $xpath
     * @return array
     */
    public function _getActions($xpath)
    {
        $result = [];
        $actions = $xpath->query('/config/actions/action');

        /** @var \DOMNode $action */
        foreach ($actions as $action) {
            $actionId = $action->attributes->getNamedItem('id')->nodeValue;
            foreach ($action->childNodes as $label) {
                if ($label->nodeName == 'label') {
                    $result[$actionId] = $label->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * Convert module node to array
     *
     * @param $module
     * @param $moduleId
     * @return array
     */
    public function _processModule($module, $moduleId)
    {
        $result = [];
        foreach ($module->childNodes as $params) {
            switch ($params->nodeName) {
                case 'label':
                    $result['label'] = $params->nodeValue;
                    break;
                case 'models':
                    $result['model'] = $this->_processModels($params);
                    break;
                case 'events':
                    $result['actions'] = $this->_processEvents($params, $moduleId);
                    break;
                case 'config':
                    $result['config'] = $this->_processConfig($params);
                    break;
            }
        }
        return $result;
    }

    /**
     * @param $events
     * @return array
     */
    public function _processModels($events)
    {
        $result = [];
        foreach ($events->childNodes as $event) {
            if ($event->nodeName == 'class') {
                $result[] = $event->attributes->getNamedItem('name')->nodeValue;
            }
        }
        return $result;
    }

    /**
     * Convert events node to array
     *
     * @param $events
     * @param $moduleId
     * @return array
     */
    public function _processEvents($events, $moduleId)
    {
        $result = [];
        foreach ($events->childNodes as $event) {
            if ($event->nodeName == 'event') {
                $result[$event->attributes->getNamedItem('controller_action')->nodeValue] = [
                    'action' => $event->attributes->getNamedItem('action_alias')->nodeValue,
                    'module' => $moduleId
                ];
                $postDispatch = $event->attributes->getNamedItem('post_dispatch');
                if ($postDispatch !== null) {
                    $result[$event->attributes->getNamedItem('controller_action')->nodeValue]['post_dispatch'] = $postDispatch->nodeValue;
                }
            }
        }
        return $result;
    }

    /**
     * Converts config to array
     *
     * @param $configs
     * @return array
     */
    public function _processConfig($configs)
    {
        $result = [];
        foreach ($configs->childNodes as $config) {
            switch ($config->nodeName) {
                case 'trackfield':
                    $result['trackfield'] = $config->attributes->getNamedItem('method')->nodeValue;
                    break;
                case 'configpath':
                    $result['configpath'] = $config->attributes->getNamedItem('constant')->nodeValue;
                    break;
                case 'editurl':
                    $result['editurl'] = $config->attributes->getNamedItem('url')->nodeValue;
                    break;
                case 'itemfield':
                    $result['itemfield'] = $config->attributes->getNamedItem('field')->nodeValue;
                    break;
            }
        }
        return $result;
    }
}
