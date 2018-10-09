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

/**
 * Class Data
 * @package KiwiCommerce\AdminActivity\Helper
 */
class TrackField extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var string;
     */
    const SYSTEM_METHOD = 'getSystemConfigFieldData';

    /**
     * @var string;
     */
    const THEME_METHOD = 'getThemeConfigFieldData';

    /**
     * @var string;
     */
    const PRODUCT_METHOD = 'getProductFieldData';

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Activity\SystemConfig
     */
    public $systemConfig;

    /**
     * @var \KiwiCommerce\AdminActivity\Model\Activity\ThemeConfig
     */
    public $themeConfig;

    /**
     * TrackField constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \KiwiCommerce\AdminActivity\Model\Activity\SystemConfig $systemConfig
     * @param \KiwiCommerce\AdminActivity\Model\Activity\ThemeConfig $themeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \KiwiCommerce\AdminActivity\Model\Activity\SystemConfig $systemConfig,
        \KiwiCommerce\AdminActivity\Model\Activity\ThemeConfig $themeConfig
    ) {
        parent::__construct($context);
        $this->systemConfig = $systemConfig;
        $this->themeConfig = $themeConfig;
    }

    /**
     * Get product module fields
     * @return array
     */
    public function getProductFieldData()
    {
        return [
            'form_key',
            'current_product_id',
            'force_reindex_eav_required',
            'news_from_date_is_formated',
            'can_save_custom_options',
            'save_rewrites_history',
            'is_custom_option_changed',
            'special_from_date_is_formated',
            'custom_design_from_is_formated',
            'affect_product_custom_options',
            'product_has_weight',
            'check_if_is_new',
            'entity_id',
            'updated_at',
            'edit_mode',
            'gift_message_available',
            'use_config_gift_message_available',
            'created_at',
            'is_changed_websites'
        ];
    }

    /**
     * Get category module fields
     * @return array
     */
    public function getCategoryFieldData()
    {
        return [
            'form_key',
            'updated_at',
            'created_at'
        ];
    }

    /**
     * Get customer module fields
     * @return array
     */
    public function getCustomerFieldData()
    {
        return [
            'id',
            'attribute_set_id',
            'entity_id',
            'form_key',
            'check_if_is_new',
            'dob_is_formated',
            'updated_at',
            'created_at',
            'rp_token',
            'rp_token_created_at',
            'is_customer_save_transaction',
            'store_id',
            'customer_id',
            'parent_id',
            'force_process'
        ];
    }

    /**
     * Get customer group modules fields
     * @return array
     */
    public function getCustomerGroupFieldData()
    {
        return [
          'customer_group_id',
          'check_if_is_new'
        ];
    }

    /**
     * Get catalog promotion modules fields
     * @return array
     */
    public function getCatalogPromotionFieldData()
    {
        return [
            'rule_id',
            'form_key',
            'check_if_is_new'
        ];
    }

    /**
     * Get cart promotion modules fields
     * @return array
     */
    public function getCartPromotionFieldData()
    {
        return [
            'is_rss',
            'form_key',
            'check_if_is_new',
            'rule_id'
        ];
    }

    /**
     * Get email modules fields
     * @return array
     */
    public function getEmailFieldData()
    {
        return [
            'template_id',
            'check_if_is_new',
            'form_key',
            'template_actual',
            'code',
            'subject',
            'sender_name',
            'sender_email',
            'text',
            'key'
        ];
    }

    /**
     * Get page modules fields
     * @return array
     */
    public function getPageFieldData()
    {
        return [
            'page_id',
            'form_key',
            'check_if_is_new',
            'store_code',
            'first_store_id'
        ];
    }

    /**
     * Get block modules fields
     * @return array
     */
    public function getBlockFieldData()
    {
        return [
            'block_id',
            'form_key',
            'check_if_is_new',
            'store_code'
        ];
    }

    /**
     * Get widget modules fields
     * @return array
     */
    public function getWidgetFieldData()
    {
        return [
            'check_if_is_new',
            'instance_id'
        ];
    }

    /**
     * Get theme configuration field data
     * @return array
     */
    public function getThemeConfigFieldData()
    {
        return [
            'back',
            'scope',
            'scope_id',
            'form_key',
            'head_includes'
        ];
    }

    /**
     * Get theme schedule field data
     * @return array
     */
    public function getThemeScheduleFieldData()
    {
        return [
            'store_id',
            'check_if_is_new'
        ];
    }

    /**
     * Get system config field data
     * @return array
     */
    public function getSystemConfigFieldData()
    {
        return [
            'check_if_is_new',
        ];
    }

    /**
     * Get attribute modules fields
     * @return array
     */
    public function getAttributeFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'attribute_id',
            'id',
            'modulePrefix'
        ];
    }

    /**
     * Get attribute set modules fields
     * @return array
     */
    public function getAttributeSetFieldData()
    {
        return [
            'entity_type_id',
            'check_if_is_new',
            'id'
        ];
    }

    /**
     * Get attribute set modules fields
     * @return array
     */
    public function getReviewRatingFieldData()
    {
        return [
            'rating_id',
            'check_if_is_new'
        ];
    }

    /**
     * Get review modules fields
     * @return array
     */
    public function getReviewFieldData()
    {
        return [
            'form_key',
            'entity_id',
            'check_if_is_new',
            'review_id',
            'entity_pk_value'
        ];
    }

    /**
     * Get admin user modules fields
     * @return array
     */
    public function getAdminUserFieldData()
    {
        return [
            'form_key',
            'password_confirmation',
            'current_password',
            'limit',
            'user_roles',
            'check_if_is_new',
            'user_id'
        ];
    }

    /**
     * Get admin user role modules fields
     * @return array
     */
    public function getAdminUserRoleFieldData()
    {
        return [
            'name',
            'check_if_is_new',
            'role_id'
        ];
    }

    /**
     * Get order modules fields
     * @return array
     */
    public function getOrderFieldData()
    {
        return [
            'check_if_is_new',
            'created_at',
            'updated_at',
            'entity_id',
            'id',
            'protect_code'
        ];
    }

    /**
     * Get tax rule modules fields
     * @return array
     */
    public function getTaxRuleFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'id',
            'tax_calculation_rule_id'
        ];
    }

    /**
     * Get tax rate modules fields
     * @return array
     */
    public function getTaxRateFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'tax_calculation_rate_id'
        ];
    }

    /**
     * Get url rewrites modules fields
     * @return array
     */
    public function getUrlRewriteFieldData()
    {
        return [
            'url_rewrite_id',
            'store_id'
        ];
    }

    /**
     * Get search term modules fields
     * @return array
     */
    public function getSearchTermFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'query_id'
        ];
    }

    /**
     * Get search synonyms modules fields
     * @return array
     */
    public function getSearchSynonymsFieldData()
    {
        return [];
    }

    /**
     * Get sitemap modules fields
     * @return array
     */
    public function getSitemapFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'store_id',
            'sitemap_id'
        ];
    }

    /**
     * Get checkout agreement modules fields
     * @return array
     */
    public function getCheckoutAgreementFieldData()
    {
        return [
            'form_key',
            'check_if_is_new',
            'id'
        ];
    }

    /**
     * Get Order satus modules fields
     * @return array
     */
    public function getOrderStatusFieldData()
    {
        return [
            'form_key',
            'check_if_is_new'
        ];
    }

    /**
     * Get System store modules fields
     * @return array
     */
    public function getSystemStoreFieldData()
    {
        return [
            'check_if_is_new'
        ];
    }

    /**
     * Get integration modules fields
     * @return array
     */
    public function getIntegrationFieldData()
    {
        return [
            'form_key',
            'current_password',
            'integration_id',
            'check_if_is_new',
            'consumer_id',
            'consumer_key',
            'consumer_secret',
            'identity_link_url'
        ];
    }

    /**
     * Get Edit fields which will skip
     * @return array
     */
    public function getSkipEditFieldData()
    {
        return [
            'region_code',
            'default_shipping',
            'default_billing',
            'is_default_billing',
            'is_default_shipping',
            'url_key_create_redirect',
            'attribute_set_id',
            'rp_token',
            'rp_token_created_at',
            'Page',
            'role_id',
            'field',
            'group_id',
            'scope',
            'id',
            'path',
            'config_id',
            'use_config_gift_message_available',
            'new_variations_attribute_set_id',
            'can_save_configurable_attributes',
            'type_has_options',
            'type_has_required_options',
            'special_to_date_is_formated',
            'custom_design_to_is_formated',
            'news_to_date_is_formated',
            'is_changed_categories',
            'url_key_create_redirect',
            'save_rewrites_history',
            'custom_design_from_is_formated',
            'custom_design_to_is_formated',
            'image_label',
            'small_image_label',
            'thumbnail_label'
        ];
    }

    /**
     * Get all fields by method
     * @param $method
     * @return array
     */
    public function getFields($method)
    {
        $fieldArray = [];
        if (!empty($method) && method_exists($this, $method)) {
            $fieldArray = $this->{$method}();
        }
        return $fieldArray;
    }

    /**
     * Get added activity data
     * @param $model
     * @param $method
     * @return array
     */
    public function getAddData($model, $method)
    {
        $skipFieldArray = $this->getFields($method);

        $logData = [];
        if (!empty($model->getData()) && is_array($model->getData())) {
            $logData = $this->getWildCardData($model, $method);
            foreach ($model->getData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $skipFieldArray) || empty($value)) {
                    continue;
                }
                $logData[$key] = [
                    'old_value' => '',
                    'new_value' => $value
                ];
            }
        }
        return $logData;
    }

    /**
     * Get edited activity data
     * @param $model
     * @param $method
     * @return array
     */
    public function getEditData($model, $method)
    {
        $fieldArray = $this->getFields($method);
        $skipFieldArray = $this->getSkipEditFieldData();

        if (\KiwiCommerce\AdminActivity\Helper\Data::isWildCardModel($model)) {
            if ($method==self::SYSTEM_METHOD) {
                return $this->systemConfig->getEditData($model, $fieldArray);
            } elseif ($method==self::THEME_METHOD) {
                return $this->themeConfig->getEditData($model, $fieldArray);
            }
        }

        $logData = [];
        if (!empty($model->getData()) && is_array($model->getData())) {
            $logData = $this->getWildCardData($model, $method);
            $skipFieldArray = array_merge($skipFieldArray, $fieldArray);
            foreach ($model->getData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $skipFieldArray)) {
                    continue;
                }
                $newData = !empty($value) ? $value : '';
                $oldData = !empty($model->getOrigData($key)) ? $model->getOrigData($key) : '';
                if (!empty($newData) || !empty($oldData)) {
                    if ($newData != $oldData) {
                        $logData[$key] = [
                            'old_value' => $oldData,
                            'new_value' => $newData
                        ];
                    }
                }
            }
        }
        return $logData;
    }

    /**
     * Get deleted activity data
     * @param $model
     * @param $method
     * @return array
     */
    public function getDeleteData($model, $method)
    {
        $fieldArray = $this->getFields($method);

        $logData = [];
        if (!empty($model->getOrigData()) && is_array($model->getOrigData())) {
            $logData = $this->getWildCardData($model, $method);
            foreach ($model->getOrigData() as $key => $value) {
                if ($this->validateValue($model, $key, $value, $fieldArray) || empty($value)) {
                    continue;
                }
                $logData[$key] = [
                    'old_value' => $value,
                    'new_value' => ''
                ];
            }
        }
        return $logData;
    }

    /**
     * Get wild data
     * @param $model
     * @param $method
     * @return array
     */
    public function getWildCardData($model, $method)
    {
        $logData = [];
        if ($method == self::PRODUCT_METHOD) {
            $newQty = $model->getData('stock_data');
            $oldQty = $model->getOrigData('quantity_and_stock_status');
            if (isset($newQty['qty']) && isset($oldQty['qty']) && $newQty['qty'] != $oldQty['qty']) {
                $logData['qty'] = [
                    'old_value' => $oldQty['qty'],
                    'new_value' => $newQty['qty']
                ];
            }
        }

        return $logData;
    }

    /**
     * Skip this fields while tracking activity log
     * @param $model
     * @param $key
     * @param $value
     * @param $skipFields
     * @return bool
     */
    public function validateValue($model, $key, $value, $skipFields)
    {
        if (is_array($value) || is_object($value) || is_array($model->getOrigData($key))
            || in_array($key, $skipFields)) {
            return true;
        }
        return false;
    }
}
