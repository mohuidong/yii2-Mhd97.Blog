<?php

namespace api\modules\v1\controllers;

use common\models\Answer;
use common\models\Question;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;

class IssueController extends BaseController
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

    public function actionIndex($perPage = 10)
    {
        $query = Question::find()->select([
            '{{%question}}.id',
            '{{%user}}.nickname nickname',
            '{{%user}}.avatar avatar',
            '{{%question}}.question',
            '{{%question}}.content',
            '{{%question}}.best_id',
            '{{%question}}.best_user_id',
            '{{%question}}.created_at',
            '{{%question}}.status'])
            ->leftJoin("{{%user}}", "{{%user}}.id = {{%question}}.user_id")
            ->where(['in', '{{%question}}.status', [Question::STATUS_PASS, Question::STATUS_SOLVE]])
            ->orderBy('{{%question}}.created_at DESC')
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
        $query = Question::find()->select([
            '{{%question}}.id',
            '{{%user}}.nickname nickname',
            'CONCAT("'. \Yii::$app->params['domain'] .'", {{%user}}.avatar) AS avatar',
            '{{%question}}.question',
            '{{%question}}.user_id',
            '{{%question}}.content',
            '{{%question}}.best_id',
            '{{%question}}.best_user_id',
            '{{%question}}.created_at',
            '{{%question}}.status'])
            ->leftJoin("{{%user}}", "{{%user}}.id = {{%question}}.user_id")
            ->where(['{{%question}}.id' => $id])
            ->andwhere(['in', '{{%question}}.status', [Question::STATUS_PASS, Question::STATUS_SOLVE]])
            ->asArray()->one();

        $accessToken = Yii::$app->request->get('access-token');
        $query['isWriter'] = 0;
        if ($query['status'] == Question::STATUS_PASS) {
            if (!empty($accessToken)) {
                $user = User::findIdentityByAccessToken($accessToken);
                $userId = $user['id'] ?:null;
            }
            if ($query['user_id'] == $userId && $query['status'] == Question::STATUS_PASS) {
                $query['isWriter'] = 1;
            }
        }

        return $query ?: [];
    }

    public function actionPerson($perPage = 10)
    {
        $id = Yii::$app->user->getId();
        $query = Question::find()->select([
            '{{%question}}.id',
            '{{%question}}.question',
            '{{%question}}.user_id',
            '{{%question}}.created_at',
            '{{%question}}.status'])
            ->orderBy('{{%question}}.created_at DESC')
            ->where(['{{%question}}.user_id' => $id])
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

    public function actionCreate()
    {
        $title = Yii::$app->request->getBodyParam('title');
        $content = Yii::$app->request->getBodyParam('content');
        $model = new Question();
        $userId = Yii::$app->user->getId();
        $model->user_id = $userId;
        $model->question = $title;
        $model->content = $content;

        if ($model->save() == false) {
            throw new BadRequestHttpException('创建失败');
        }
    }

    public function actionUpdate($id, $answer)
    {
        $model = Question::findOne($id);
        $user = Yii::$app->user->getId();
        $answerModel = Answer::findOne($answer);

        if ($user != $model->user_id) {
            throw new BadRequestHttpException('权限异常');
        }
        if ($model->status != Question::STATUS_PASS) {
            throw new BadRequestHttpException('状态异常');
        }

        $model->best_id = $answer;
        $model->best_user_id = $answerModel->user_id;
        $model->status = Question::STATUS_SOLVE;

        if ($model->save() == false) {
            throw new BadRequestHttpException('采纳失败');
        }
    }
}
