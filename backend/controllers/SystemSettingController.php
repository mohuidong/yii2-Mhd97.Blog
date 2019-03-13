<?php

namespace backend\controllers;

use Yii;
use common\models\SystemSetting;
use common\models\Posts;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\validators\NumberValidator;
use yii\validators\RegularExpressionValidator;

class SystemSettingController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true ,
                        'roles' => ['SystemSettingList'],
                    ],
                    [
                        'actions' => ['update','update-goods', 'get-information', 'get-goods'],
                        'allow' => true,
                        'roles' => ['SystemSettingCud'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST', 'GET'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $arr = SystemSetting::getType();
        $arrs = [];
        foreach ($arr as $k => $v){
            $arrs[$k] = SystemSetting::getArrs($k);
        }
        return $this->render('index', [
            'systemSetting' => $arrs,
            'type' => systemSetting::getType(),
        ]);

    }

    /**
     * Updates an existing FinanceSystemSetting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($key)
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();
        if ($key) {
            switch ($key) {
                case SystemSetting::KEY_COUNT_REGISTRATIONS:
                    $validator = new RegularExpressionValidator(['pattern' => '/^\d+$/']);
                    if ($validator->validate($post['value'], $error) == false) {
                        $response['message'] = SystemSetting::getType()[$key] . ':' . $error;
                        break;
                    }
                    if (SystemSetting::saveSetting($post['value'], $key)) {
                        $response['status'] = 1;
                        $response['message'] = '操作成功';
                    }
                    break;
                case SystemSetting::KEY_CUSTOMER_SERVICE_EMAIL:
                    $validator = new RegularExpressionValidator(['pattern' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/']);
                    if ($validator->validate($post['value'], $error) == false) {
                        $response['message'] = SystemSetting::getType()[$key] . ':' . $error;
                        break;
                    }
                    if (SystemSetting::saveSetting($post['value'], $key)) {
                        $response['status'] = 1;
                        $response['message'] = '操作成功';
                    }
                    break;
                default:
                    if (SystemSetting::saveSetting($post['value'], $key)) {
                        $response['status'] = 1;
                        $response['message'] = '操作成功';
                    }else{
                        $response['message'] = '无效的操作';
                    }
                    break;
            }
        }

        return $response;
    }

    public function actionGetInformation()
    {
        $response = ['status' => 0, 'message' => '查询失败, 请重试', 'data' => []];
        Yii::$app->response->format = 'json';
        $list = Posts::find()
            ->select([
                'id',
                'title',
            ])->where([
                'class' => Posts::CLASS_INDUSTRY_INFORMATION,
            ])->asArray()->all();
        if ($list) {
            $response = ['status' => 1, 'message' => '查询成功', 'data' => $list];
        }
        return $response;
    }
}
