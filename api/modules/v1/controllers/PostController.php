<?php

namespace api\modules\v1\controllers;

use common\models\Posts;
use yii\data\ActiveDataProvider;

class PostController extends BaseController
{
    public function actionIndex($perPage = 5)
    {
//        if (!ArrayHelper::keyExists($class, Posts::$class)) {
//            throw new BadRequestHttpException('无效的参数：class');
//        }
        $query =  Posts::find()->select([
            'id',
            'cat_id',
            'title',
            'summary',
            'content',
            'user_name',
            'created_at',
        ])->where(['status' => Posts::STATUS_PASS])->orderBy('created_at DESC');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'validatePage' => false,
            ],
        ]);

        return $provider;
    }

    public function actionView($id)
    {
        $post = Posts::find()->select([
            'title',
            'summary',
            'content',
            'CONCAT("'. \Yii::$app->params['domain'] .'", label_img) AS label_img',
            'user_name',
            'likes',
            'created_at',
        ])->where(['id' => $id, 'status' => Posts::STATUS_PASS])->one();

        return $post ?: [];
    }
}
