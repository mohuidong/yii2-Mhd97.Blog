<?php

namespace api\modules\v1\controllers;

use api\models\Form\ApiCodeLoginForm;
use api\models\Form\ApiLoginForm;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * Session controller for the `v1` module
 */
class SessionController extends BaseController
{
    /**
     * 登陆
     * @return array
     */
    public function actionCreate($mode)
    {
        $request = \Yii::$app->request;
        $phone = $request->getBodyParam('phone');
        $password = $request->getBodyParam('password');
        $code = $request->getBodyParam('code');

        switch ($mode) {
            case 'pd':
                $model = new ApiLoginForm();
                $model->phone = $phone;
                $model->password = $password;
                break;
            case 'code':
                $model = new ApiCodeLoginForm();
                $model->phone = $phone;
                $model->code = $code;
                break;
            default:
                throw new BadRequestHttpException('无效的参数：mode');
                break;
        }

        $res = $model->login();

        if ($res) {
            return $res;
        } else {
            throw new BadRequestHttpException($model->getFirstErrors() ? array_values($model->getFirstErrors())[0] : '登录失败，请重试');
        }
    }
}
