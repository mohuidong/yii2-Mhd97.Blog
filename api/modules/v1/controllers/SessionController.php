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
    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $username = $request->getBodyParam('username');
        $password = $request->getBodyParam('password');

        $model = new ApiLoginForm();
        $model->username = $username;
        $model->password = $password;

        $res = $model->login();

        if ($res) {
            return $res;
        } else {
            throw new BadRequestHttpException($model->getFirstErrors() ? array_values($model->getFirstErrors())[0] : '登录失败，请重试');
        }
    }
}
