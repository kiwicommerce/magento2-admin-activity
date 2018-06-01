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
namespace KiwiCommerce\AdminActivity\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 * @package KiwiCommerce\AdminActivity\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var string
     */
    const ACTIVITY_ENABLE = 'admin_activity/general/enable';

    /**
     * @var string
     */
    const LOGIN_ACTIVITY_ENABLE = 'admin_activity/general/login_activity';

    /**
     * @var string
     */
    const PAGE_VISIT_ENABLE = 'admin_activity/general/page_visit';

    /**
     * @var string
     */
    const CLEAR_LOG_DAYS = 'admin_activity/general/clearlog';

    /**
     * @var string
     */
    const MODULE_ORDER = 'admin_activity/module/order';

    /**
     * @var string
     */
    const MODULE_PRODUCT = 'admin_activity/module/product';

    /**
     * @var string
     */
    const MODULE_CATEGORY = 'admin_activity/module/category';

    /**
     * @var string
     */
    const MODULE_CUSTOMER = 'admin_activity/module/customer';

    /**
     * @var string
     */
    const MODULE_PROMOTION = 'admin_activity/module/promotion';

    /**
     * @var string
     */
    const MODULE_EMAIL = 'admin_activity/module/email';

    /**
     * @var string
     */
    const MODULE_PAGE = 'admin_activity/module/page';

    /**
     * @var string
     */
    const MODULE_BLOCK = 'admin_activity/module/block';

    /**
     * @var string
     */
    const MODULE_WIDGET = 'admin_activity/module/widget';

    /**
     * @var string
     */
    const MODULE_THEME = 'admin_activity/module/theme';

    /**
     * @var string
     */
    const MODULE_SYSTEM_CONFIG = 'admin_activity/module/system_config';

    /**
     * @var string
     */
    const MODULE_ATTRIBUTE = 'admin_activity/module/attibute';

    /**
     * @var string
     */
    const MODULE_ADMIN_USER = 'admin_activity/module/admin_user';

    /**
     * @var string
     */
    const MODULE_SEO = 'admin_activity/module/seo';

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Config
     */
    public $config;

    /**
     * @var array
     */
    public static $wildcardModels = [
        \Magento\Framework\App\Config\Value\Interceptor::class
    ];

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \KiwiCommerce\AdminActivity\Model\Config $config
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \KiwiCommerce\AdminActivity\Model\Config $config
    ) {
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Check and return status of module
     * @return bool
     */
    public function isEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for login activity
     * @return bool
     */
    public function isLoginEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $loginStatus = $this->scopeConfig
            ->isSetFlag(self::LOGIN_ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $loginStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Check and return status for page visit history
     * @return bool
     */
    public function isPageVisitEnable()
    {
        $status = $this->scopeConfig->isSetFlag(self::ACTIVITY_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        $pageVisitStatus = $this->scopeConfig
            ->isSetFlag(self::PAGE_VISIT_ENABLE, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($status == '1' && $pageVisitStatus == '1') {
            return true;
        }

        return false;
    }

    /**
     * Get value of system config from path
     * @param $path
     * @return bool
     */
    public function getConfigValue($path)
    {
        $moduleValue = $this->scopeConfig->getValue(
            constant(
                'self::'
                . $path
            ),
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        if ($moduleValue) {
            return $moduleValue;
        }
        return false;
    }

    /**
     * Get translated label by action name
     * @param string $action
     * @return string
     */
    public function getActionTranslatedLabel($action)
    {
        return $this->config->getActionLabel($action);
    }

    /**
     * Get all actions
     * @return array
     */
    public function getAllActions()
    {
        return $this->config->getActions();
    }

    /**
     * Get activity module name
     * @return bool
     */
    public function getActivityModuleName($module)
    {
        return $this->config->getActivityModuleName($module);
    }

    /**
     * Get module name is valid or not
     * @param $model
     * @return bool
     */
    public static function isWildCardModel($model)
    {
        $model = is_string($model)?$model:get_class($model);
        if (in_array($model, self::$wildcardModels)) {
            return true;
        }
        return false;
    }
}
