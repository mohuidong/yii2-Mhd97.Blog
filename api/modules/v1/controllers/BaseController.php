<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\Response;
use api\models\Sign;
use yii\web\HttpException;

class BaseController extends Controller {

    public $serializer = [
        'class' => 'common\components\ApiSerializer',
        'collectionEnvelope' => 'items',
        'additionalAttributes' => [],
    ];

    // accessToken过期时间
    protected $accessTokenExpiresIn = 0;

    // 跳过超时判断
    protected $skipOvertime = [];

    // 版本
    protected $version = '0.0.0';

    protected $appid;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [
                        'X-Pagination-Current-Page',
                        'X-Pagination-Page-Count',
                        'X-Pagination-Per-Page',
                        'X-Pagination-Total-Count',
                        'Link',
                    ],
                ],

            ],
        ];
    }

    public function init()
    {
        parent::init();
        //$this->accessTokenExpiresIn = $this->config->app->sessionTime;
        $this->version = Yii::$app->request->getHeaders('version');
        $this->appid = Yii::$app->request->get('appid');
    }

    public function beforeAction($action) {
        if ($this->isPublic()) {
            return parent::beforeAction($action);
        }

        parse_str(Yii::$app->request->getQueryString(), $queryParams);

        if ((YII_ENV_DEV || YII_ENV_TEST) && isset($queryParams['debug'])) {
            return parent::beforeAction($action);
        }

        $parameters = [
            'appid',
            'sign',
            'timestamp',
        ];

        foreach ($parameters as $parameter) {
            if (empty($queryParams[$parameter])) {
                throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                    'params' => $parameter,
                ]));
            }
        }

        if (($queryParams['timestamp'] > (TIMESTAMP + 1000)) || ($queryParams['timestamp'] < (TIMESTAMP - 1000))) {
            if (!$this->isSkipOvertime()) {
                throw new BadRequestHttpException('请求已过期');
            }
        }


        $secret = isset(Yii::$app->params['secret'][$queryParams['appid']]) ? Yii::$app->params['secret'][$queryParams['appid']] : '';
        $sign = (new Sign())->create($queryParams, $secret);

        if ($sign != $queryParams['sign']) {
            throw new UnauthorizedHttpException('签名错误');
        }

        return parent::beforeAction($action);
    }

    public function actionOptions()
    {
        return true;
    }

    /**
     * 公共访问的api
     *
     * @var array
     */
    protected $publicApi = [
        'any' => [
            'notify',
        ],
        /*'action' => [
            'notify@hc',
        ]*/
    ];

    protected function isPublic()
    {
        $controller = Yii::$app->controller->id;

        foreach ($this->publicApi as $method => $arg) {

            switch ($method) {
                case 'any':
                    if (in_array($controller, $arg)) {
                        return true;
                    }
                    break;
                case 'action':
                    $action = Yii::$app->controller->action->id;
                    $needle = "{$controller}@{$action}";

                    if (in_array($needle, $arg)) {
                        return true;
                    }
                    break;
            }
        }

        return false;
    }

    /**
     * 跳过超时验证
     * @return bool
     */
    protected function isSkipOvertime()
    {
        return false;
    }

    /**
     * 抛出Http异常
     * @param $status
     * @param $message
     * @param $code
     * @param null $previous
     * @return HttpException
     */
    public function httpException($status, $message, $code, $previous = null)
    {
        return new HttpException($status, $message, $code, $previous);
    }
}
