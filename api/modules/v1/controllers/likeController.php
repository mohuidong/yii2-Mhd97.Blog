<?php

namespace api\modules\v1\controllers;

use common\models\Likes;
use Yii;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;

class LikeController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        echo '111';
    }

    public function actionView($id)
    {
        $user = Yii::$app->user->identity;
        $likesStatus = Likes::find()->select(['status'])->where(['user_id' => $user->getId(), 'posts_id' => $id])->one();
        return $likesStatus ? :0;
    }
}
