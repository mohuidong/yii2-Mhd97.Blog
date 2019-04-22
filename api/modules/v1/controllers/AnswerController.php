<?php

namespace api\modules\v1\controllers;

use common\models\Answer;
use common\models\Question;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;

class AnswerController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if(!in_array(Yii::$app->controller->action->id, ['index', 'view']))
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    public function actionIndex($id, $perPage = 10)
    {
        $query = Answer::find()->select([
            '{{%answer}}.id',
            '{{%user}}.nickname nickname',
            'CONCAT("'. \Yii::$app->params['domain'] .'", {{%user}}.avatar) AS avatar',
            '{{%answer}}.answer',
            '{{%answer}}.created_at'
        ])
            ->where(['question_id' => $id])
            ->leftJoin("{{%user}}", "{{%user}}.id = {{%answer}}.user_id")
            ->asArray();

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
        $bestId = Question::findOne($id)->best_id;
        if ($bestId < 1) {
            throw new BadRequestHttpException('当前问题没有最优回答');
        }
        $model = Answer::find()->select([
            '{{%answer}}.id',
            '{{%user}}.nickname nickname',
            'CONCAT("'. \Yii::$app->params['domain'] .'", {{%user}}.avatar) AS avatar',
            '{{%answer}}.answer',
            '{{%answer}}.created_at'
            ])
            ->where(['{{%answer}}.id' => $bestId])
            ->leftJoin("{{%user}}", "{{%user}}.id = {{%answer}}.user_id")
            ->asArray()
            ->one();
        return $model ?: [];
    }

    public function actionCreate($id)
    {
        $model = new Answer();
        $content = Yii::$app->request->getBodyParam('replyContent');

        $userId = Yii::$app->user->getId();
        $model->answer = $content;
        $model->question_id = $id;
        $model->user_id = $userId;
        $model->created_at = TIMESTAMP;

        if ($model->save() == false) {
            throw new BadRequestHttpException(implode(';', array_values($model->getFirstErrors())));
        }
    }
}
