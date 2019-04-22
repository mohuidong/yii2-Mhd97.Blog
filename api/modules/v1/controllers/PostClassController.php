<?php

namespace api\modules\v1\controllers;

use common\models\PostClass;
use Yii;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;

class PostClassController extends BaseController
{
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => QueryParamAuth::className(),
//        ];
//        return $behaviors;
//    }

    public function actionIndex()
    {
        $postClass = PostClass::find()
            ->select(['id','class_name'])
            ->orderBy('order')
            ->asArray()->all();
        return $postClass;
    }

    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
        $likesStatus = PostClass::find()->select(['status'])->where(['user_id' => $user->getId(), 'posts_id' => $id])->one();
        return $likesStatus ? :0;
    }
}
