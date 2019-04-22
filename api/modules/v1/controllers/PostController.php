<?php

namespace api\modules\v1\controllers;

use common\models\PostClass;
use common\models\Posts;
use yii\data\ActiveDataProvider;
use common\models\Likes;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class PostController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (!in_array(Yii::$app->controller->action->id, ['index', 'view'])) {
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
        }
        return $behaviors;
    }

    public function actionIndex($perPage = 10, $t = null)
    {
        $query =  Posts::find()->select([
            '{{%posts}}.id',
            '{{%post_class}}.class_name cat_name',
            '{{%posts}}.title',
            '{{%posts}}.summary',
            '{{%posts}}.content',
            '{{%posts}}.user_name',
            '{{%posts}}.created_at',
            ])->leftJoin("{{%post_class}}", "{{%post_class}}.id = {{%posts}}.cat_id")
            ->where(['{{%posts}}.status' => Posts::STATUS_PASS])
            ->orderBy('{{%posts}}.created_at DESC')->asArray();

        if (ArrayHelper::keyExists($t, Posts::$class)) {
            $query->andWhere(['{{%posts}}.cat_id' => $t]);
        }

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

    public function actionLikes($id)
    {
        $user = \Yii::$app->user->identity;
        $count = Likes::find()->where(['user_id' => $user['id'], 'posts_id' => $id])->one();
        if (!$count) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();

                $model = new Likes();
                $model->posts_id = $id;
                $model->user_id = $user['id'];
                $model->ip = \Yii::$app->request->userIP;
                $model->status = Likes::LIKES_YES;
                $model->created_at = TIMESTAMP;
                if ($model->save() == false) {
                    throw new \Exception(implode(';', array_values($model->getFirstErrors())));
                }

                $posts = Posts::findOne($id);
                $posts->likes = $posts->likes + 1;
                if ($posts->save() == false) {
                    throw new \Exception(implode(';', array_values($posts->getFirstErrors())));
                }
                $transaction->commit();
                $response['message'] = '修改成功';
                $response['name'] = 'SUCCESSFUL_OPERATION';
                $response['likes_status'] = $model->status;
                $response['likes'] = $posts->likes;
                return $response;
            } catch(\Exception $e) {
                \Yii::error($e->getMessage(), 'CreateLikes');
                $transaction->rollBack();
                throw new \Exception($e->getMessage());
            } catch(\Throwable $e) {
                \Yii::error($e->getMessage(), 'CreateLikes');
                $transaction->rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            if ($count->status == Likes::LIKES_YES) {
                try {
                    $transaction = \Yii::$app->db->beginTransaction();

                    $count->status = Likes::LIKES_CANCEL;
                    $count->created_at = TIMESTAMP;
                    if ($count->save() == false) {
                        throw new \Exception(implode(';', array_values($count->getFirstErrors())));
                    }

                    $posts = Posts::findOne($id);
                    $posts->likes = $posts->likes - 1;
                    if ($posts->save() == false) {
                        throw new \Exception(implode(';', array_values($posts->getFirstErrors())));
                    }

                    $transaction->commit();
                    $response['message'] = '修改成功';
                    $response['name'] = 'SUCCESSFUL_OPERATION';
                    $response['likes_status'] = $count->status;
                    $response['likes'] = $posts->likes;
                    return $response;
                } catch(\Exception $e) {
                    \Yii::error($e->getMessage(), 'CreateLikes');
                    $transaction->rollBack();
                    throw new \Exception($e->getMessage());
                } catch(\Throwable $e) {
                    \Yii::error($e->getMessage(), 'CreateLikes');
                    $transaction->rollBack();
                    throw new \Exception($e->getMessage());
                }
            } else if ($count->status == Likes::LIKES_CANCEL) {
                try {
                    $transaction = \Yii::$app->db->beginTransaction();

                    $count->status = Likes::LIKES_YES;
                    $count->created_at = TIMESTAMP;
                    if ($count->save() == false) {
                        throw new \Exception(implode(';', array_values($count->getFirstErrors())));
                    }

                    $posts = Posts::findOne($id);
                    $posts->likes = $posts->likes + 1;
                    if ($posts->save() == false) {
                        throw new \Exception(implode(';', array_values($posts->getFirstErrors())));
                    }

                    $transaction->commit();
                    $response['message'] = '修改成功';
                    $response['name'] = 'SUCCESSFUL_OPERATION';
                    $response['likes_status'] = $count->status;
                    $response['likes'] = $posts->likes;
                    return $response;
                } catch(\Exception $e) {
                    \Yii::error($e->getMessage(), 'CreateLikes');
                    $transaction->rollBack();
                    throw new \Exception($e->getMessage());
                } catch(\Throwable $e) {
                    \Yii::error($e->getMessage(), 'CreateLikes');
                    $transaction->rollBack();
                    throw new \Exception($e->getMessage());
                }
            }
        }
    }
}
