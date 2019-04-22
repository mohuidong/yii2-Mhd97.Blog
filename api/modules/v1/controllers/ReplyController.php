<?php

namespace api\modules\v1\controllers;

use common\models\Reply;
use Yii;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;

class ReplyController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (!in_array(Yii::$app->controller->action->id, ['view'])) {
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
        }
        return $behaviors;
    }

    public function actionView($id, $perPage = 5)
    {

        $query =  Reply::find()->select([
            '{{%reply}}.reply_id',
            '{{%reply}}.user_id',
            '{{%reply}}.posts_id',
            '{{%reply}}.content',
            '{{%reply}}.created_at',
            '{{%user}}.nickname userName'])
            ->leftJoin("{{%user}}", "{{%reply}}.user_id = {{%user}}.id")
            ->where(['{{%reply}}.posts_id' => $id])
            ->orderBy('{{%reply}}.created_at DESC')->asArray();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'validatePage' => false,
            ],
        ]);

        return $provider;
    }

    public function actionCreate($id)
    {
        $model = new Reply();
        $content = Yii::$app->request->getBodyParam('replyContent');

        $userId = Yii::$app->user->getId();
        $model->content = $content;
        $model->posts_id = $id;
        $model->user_id = $userId;
        $model->created_at = TIMESTAMP;

        if ($model->save() == false) {
            throw new BadRequestHttpException(implode(';', array_values($model->getFirstErrors())));
        }
    }
}
