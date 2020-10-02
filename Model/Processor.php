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
namespace KiwiCommerce\AdminActivity\Model;

use KiwiCommerce\AdminActivity\Api\ActivityRepositoryInterface;
use \KiwiCommerce\AdminActivity\Helper\Data as Helper;

/**
 * Class Processor
 * @package KiwiCommerce\AdminActivity\Model
 */
class Processor
{
    /**
     * @var string
     */
    const PRIMARY_FIELD = 'id';

    /**
     * @var array
     */
    const SKIP_MODULE_ACTIONS = [
        'mui_index_render',
        'adminactivity_activity_index',
        'adminactivity_activity_log',
        'adminactivity_activity_revert'
    ];

    /**
     * @var string
     */
    const SKIP_MODULE = [
        'mui'
    ];

    /**
     * @var string
     */
    const SALES_ORDER = 'sales_order';

    /**
     * @var string
     */
    const SAVE_ACTION = 'save';

    /**
     * @var string
     */
    const EDIT_ACTION = 'edit';

    /**
     * @var Config
     */
    public $config;

    /**
     * current event config
     * @var array
     */
    public $eventConfig;

    /**
     * Last action name
     * @var string
     */
    public $actionName = '';

    /**
     * Last full action name
     * @var string
     */
    public $lastAction = '';

    /**
     * Initialization full action name
     * @var string
     */
    public $initAction = '';

    /**
     * Temporary storage for model changes before saving to table.
     * @var array
     */
    public $activityLogs = [];

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    public $authSession;

    /**
     * @var Handler
     */
    public $handler;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    public $remoteAddress;

    /**
     * @var ActivityFactory
     */
    public $activityFactory;

    /**
     * @var ActivityLogDetailFactory
     */
    public $activityDetailFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    public $dateTime;

    /**
     * @var ActivityRepositoryInterface
     */
    public $activityRepository;

    /**
     * @var Helper
     */
    public $helper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;

    /**
     * Request
     *
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * Http request
     * @var \Magento\Framework\App\Request\Http
     */
    public $httpRequest;
    /**
     * @var Activity\Status
     */
    public $status;

    /**
     * @var Handler\PostDispatch
     */
    public $postDispatch;

    /**
     * @var array
     */
    public $urlParams = [
        '{{module}}',
        '{{controller}}',
        '{{action}}',
        '{{field}}',
        '{{id}}'
    ];

    /**
     * Processor constructor.
     * @param Config $config
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param Handler $handler
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param ActivityFactory $activityFactory
     * @param ActivityLogDetailFactory $activityDetailFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     * @param ActivityRepositoryInterface $activityRepository
     * @param Helper $helper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param Activity\Status $status
     * @param Handler\PostDispatch $postDispatch
     */
    public function __construct(
        \KiwiCommerce\AdminActivity\Model\Config $config,
        \Magento\Backend\Model\Auth\Session $authSession,
        \KiwiCommerce\AdminActivity\Model\Handler $handler,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \KiwiCommerce\AdminActivity\Model\ActivityFactory $activityFactory,
        \KiwiCommerce\AdminActivity\Model\ActivityLogDetailFactory $activityDetailFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        ActivityRepositoryInterface $activityRepository,
        Helper $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Request\Http $httpRequest,
        \KiwiCommerce\AdminActivity\Model\Activity\Status $status,
        \KiwiCommerce\AdminActivity\Model\Handler\PostDispatch $postDispatch
    ) {
        $this->config = $config;
        $this->authSession = $authSession;
        $this->handler = $handler;
        $this->remoteAddress = $remoteAddress;
        $this->activityFactory = $activityFactory;
        $this->activityDetailFactory = $activityDetailFactory;
        $this->storeManager = $storeManager;
        $this->dateTime = $dateTime;
        $this->activityRepository = $activityRepository;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->httpRequest = $httpRequest;
        $this->status = $status;
        $this->postDispatch = $postDispatch;
    }

    /**
     * Get and set event config from full action name
     * @param $fullActionName
     * @param $actionName
     * @return $this
     */
    public function init($fullActionName, $actionName)
    {
        $this->actionName = $actionName;

        if (!$this->initAction) {
            $this->initAction = $fullActionName;
        }
        $this->lastAction = $fullActionName;
        $this->eventConfig = $this->config->getEventByAction($fullActionName);
        if (isset($this->eventConfig['post_dispatch'])) {
            $this->_callPostDispatchCallback();
        }
        return $this;
    }

    /**
     * Check Model class
     * @param $model
     * @return bool
     */
    public function validate($model)
    {
        if (\KiwiCommerce\AdminActivity\Helper\Data::isWildCardModel($model)) {
            if (!empty($this->activityLogs)) {
                return false;
            }
        }

        if ($this->eventConfig) {
            $usedModel = (array)$this->config->getEventModel($this->eventConfig['module']);
            $pathConst = $this->config->getActivityModuleConstant($this->eventConfig['module']);
            if (!empty($this->helper->getConfigValue($pathConst))) {
                foreach ($usedModel as $module) {
                    if ($model instanceof $module) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Return method name of TrackField class
     * @return string
     */
    public function getMethod()
    {
        return $this->config->getTrackFieldModel($this->eventConfig['module']);
    }

    /**
     * Get item url
     * @param $model
     * @return string
     */
    public function getEditUrl($model)
    {
        $id = $model->getId();
        if ($this->eventConfig['module']==self::SALES_ORDER && (!empty($model->getOrderId())
                || !empty($model->getParentId()))) {
            $id = ($model->getOrderId()) ? $model->getOrderId() : $model->getParentId();
        }
        return str_replace(
            $this->urlParams,
            [
                $this->handler->request->getModuleName(),
                $this->handler->request->getControllerName(),
                $this->handler->request->getActionName(),
                self::PRIMARY_FIELD,
                $id
            ],
            $this->config->getActivityModuleEditUrl($this->eventConfig['module'])
        );
    }

    /**
     * Set activity data after item added
     * @param $model
     * @return $this|bool
     */
    public function modelAddAfter($model)
    {
        if ($this->validate($model)) {
            $logData = $this->handler->modelAdd($model, $this->getMethod());
            if (!empty($logData)) {
                $activity = $this->_initActivity($model);
                $activity->setIsRevertable(0);

                $this->addLog($activity, $logData, $model);
            }
        }
        return $this;
    }

    /**
     * Set activity data after item edited
     * @param $model
     * @return $this|bool
     */
    public function modelEditAfter($model)
    {
        $label = ($this->eventConfig['action'] == self::SAVE_ACTION) ? self::EDIT_ACTION : $this->eventConfig['action'];
        if ($this->validate($model)) {
            $logData = $this->handler->modelEdit($model, $this->getMethod());
            if (!empty($logData)) {
                $activity = $this->_initActivity($model);
                $activity->setActionType($label);
                $activity->setIsRevertable(1);

                $this->addLog($activity, $logData, $model);
            }
        }

        return $this;
    }

    /**
     * Set activity data after item deleted
     * @param $model
     * @return $this|bool
     */
    public function modelDeleteAfter($model)
    {
        if ($this->validate($model)) {
            $logData = $this->handler->modelDelete($model, $this->getMethod());
            if (!empty($logData)) {
                $activity = $this->_initActivity($model);

                $activity->setIsRevertable(0);
                $activity->setItemUrl('');

                $this->addLog($activity, $logData, $model);
            }
        }
        return $this;
    }

    /**
     * Set activity details data
     * @param $activity
     * @param $logData
     * @param $model
     * @return void
     */
    public function addLog($activity, $logData, $model)
    {
        $logDetail = $this->_initActivityDetail($model);
        $this->activityLogs[] = [
            \KiwiCommerce\AdminActivity\Model\Activity::class => $activity,
            \KiwiCommerce\AdminActivity\Model\ActivityLog::class => $logData,
            \KiwiCommerce\AdminActivity\Model\ActivityLogDetail::class => $logDetail
        ];
    }

    /**
     * Insert activity log data in database
     * @return bool
     */
    public function saveLogs()
    {
        try {
            if (!empty($this->activityLogs)) {
                foreach ($this->activityLogs as $model) {
                    $activity = $model[\KiwiCommerce\AdminActivity\Model\Activity::class];
                    $activity->save();
                    $activityId = $activity->getId();

                    if (isset($model[\KiwiCommerce\AdminActivity\Model\ActivityLog::class])) {
                        $logData = $model[\KiwiCommerce\AdminActivity\Model\ActivityLog::class];
                        if ($logData) {
                            foreach ($logData as $log) {
                                $log->setActivityId($activityId);
                                $log->save();
                            }
                        }
                    }

                    if (isset($model[\KiwiCommerce\AdminActivity\Model\ActivityLogDetail::class])) {
                        $detail = $model[\KiwiCommerce\AdminActivity\Model\ActivityLogDetail::class];
                        $detail->setActivityId($activityId);
                        $detail->save();
                    }
                }
                $this->activityLogs = [];
            }
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Set activity details data
     * @return Activity
     */
    public function _initLog()
    {
        $activity = $this->activityFactory->create();

        if ($this->authSession->isLoggedIn()) {
            $activity->setUsername($this->authSession->getUser()->getUsername());
            $activity->setName($this->authSession->getUser()->getName());
            $activity->setAdminId($this->authSession->getUser()->getId());
        }

        $activity->setScope($this->getScope());
        $activity->setRemoteIp($this->remoteAddress->getRemoteAddress());
        $activity->setForwardedIp($this->httpRequest->getServer('HTTP_X_FORWARDED_FOR'));
        $activity->setUserAgent($this->handler->header->getHttpUserAgent());
        if($this->eventConfig != null) {
            $activity->setModule($this->helper->getActivityModuleName($this->eventConfig['module']));
            $activity->setActionType($this->eventConfig['action']);
        }
        $activity->setFullaction($this->escapeString($this->lastAction, '/'));
        $activity->setStoreId(0);

        return $activity;
    }

    /**
     * Set activity scope, name and item url
     * @param $model
     * @return bool|Activity
     */
    public function _initActivity($model)
    {
        if (!$this->authSession->isLoggedIn()) {
            return false;
        }

        $activity = $this->_initLog();

        $activity->setStoreId($this->getStoreId($model));
        $activity->setItemName($model->getData($this->config
            ->getActivityModuleItemField($this->eventConfig['module'])));
        $activity->setItemUrl($this->getEditUrl($model));

        return $activity;
    }

    /**
     * Set activity details
     * @param $model
     * @return mixed
     */
    public function _initActivityDetail($model)
    {
        $activity = $this->activityDetailFactory->create()->setData([
            'model_class' => get_class($model),
            'item_id' => $model->getId(),
            'status' => 'success',
            'response' => ''
        ]);
        return $activity;
    }

    /**
     * Check post dispatch method to track log for mass actions
     * @return bool
     */
    public function _callPostDispatchCallback()
    {
        $handler = $this->postDispatch;
        if (isset($this->eventConfig['post_dispatch'])) {
            $callback = $this->eventConfig['post_dispatch'];
            if ($handler && $callback && method_exists($handler, $callback)) {
                $handler->{$callback}($this->eventConfig, $this);
                return true;
            }
        }

        return false;
    }

    /**
     * Get store identifier
     * @param $model
     * @return int
     */
    public function getStoreId($model)
    {
        $data = $model->getData();
        if (isset($data['scope_id'])) {
            return $model->getScopeId();
        }
        if (isset($data['store_id'])) {
            return $model->getStoreId();
        }
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get scope name
     * @return string
     */
    public function getScope()
    {
        if ($this->request->getParam('store') == 1 || $this->request->getParam('scope') == 'stores') {
            $scope = 'stores';
        } elseif ($this->request->getParam('website') == 1) {
            $scope = 'website';
        } else {
            $scope = 'default';
        }
        return $scope;
    }

    /**
     * Revert last changes made in module
     * @param $activityId
     * @return array
     */
    public function revertActivity($activityId)
    {
        $result = [
            'error' => true,
            'message' => __('Something went wrong, please try again')
        ];

        try {
            $activityModel = $this->activityFactory->create()->load($activityId);
            if ($activityModel->getIsRevertable() === '0') {
                $result['message'] = __('Activity data has already been reverted');
            } else {
                if ($activityModel->getId() && $this->activityRepository->revertActivity($activityModel)) {
                    $activityModel->setRevertBy($this->authSession->getUser()->getUsername());
                    $activityModel->setUpdatedAt($this->dateTime->gmtDate());
                    $activityModel->save();

                    $result['error'] = false;
                    $this->status->markSuccess($activityId);
                    $this->messageManager->addSuccessMessage(__('Activity data has been reverted successfully'));
                }
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $this->status->markFail($activityId);
        }

        return $result;
    }

    /**
     * Convert module and action name to user readable format
     * @param $name
     * @param string $delimiter
     * @return string
     */
    public function escapeString($name, $delimiter = ' ')
    {
        return implode(
            $delimiter,
            array_map(
                'ucfirst',
                array_filter(
                    explode(
                        '_',
                        strtolower($name)
                    )
                )
            )
        );
    }

    /**
     * Check action to skip
     * @param $module
     * @param $fullAction
     * @return bool
     */
    public function isValidAction($module, $fullAction)
    {
        if (in_array(strtolower($fullAction), self::SKIP_MODULE_ACTIONS)
            || in_array(strtolower($module), self::SKIP_MODULE)) {
            return false;
        }
        return true;
    }

    /**
     * Track page visit history
     * @param $module
     * @return void
     */
    public function addPageVisitLog($module)
    {
        if (in_array(strtolower($this->lastAction), $this->config->getControllerActions())) {
            return false;
        }

        if ($this->helper->isPageVisitEnable()
            && $this->isValidAction($module, $this->lastAction)) {

            $activity = $this->_initLog();

            $activity->setActionType('view');
            $activity->setIsRevertable(0);

            if (!$activity->getModule()) {
                $activity->setModule($this->escapeString($module));
            }

            $activity->save();
        }
    }
}
