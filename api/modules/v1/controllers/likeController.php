<?php

namespace api\modules\v1\controllers;

use common\models\Likes;
use common\models\Posts;
use yii\web\BadRequestHttpException;

class likeController extends BaseController
{
    public function actionCreate($postsId, $type)
    {
        $user = \Yii::$app->user->identity;
        $count = Likes::find()->where(['user_id' => $user['id'], 'posts_id' => $postsId])->one();
        if ($count->status == Likes::LIKES_YES) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();

                $count->status = Likes::LIKES_CANCEL;
                $count->created_at = TIMESTAMP;
                if ($count->save() == false) {
                    throw new \Exception(array_values($count->getFirstError())[0]);
                }

                $posts = Posts::findOne($postsId);
                $posts->likes = $posts->likes - 1;
                if ($posts->save() == false) {
                    throw new \Exception(array_values($posts->getFirstError())[0]);
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
                    throw new \Exception(array_values($count->getFirstError())[0]);
                }

                $posts = Posts::findOne($postsId);
                $posts->likes = $posts->likes + 1;
                if ($posts->save() == false) {
                    throw new \Exception(array_values($posts->getFirstError())[0]);
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
        } else if (empty($count)) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();

                $model = new Likes();
                $model->posts_id = $postsId;
                $model->user_id = $user['id'];
                $model->ip = \Yii::$app->request->userHostAddress;
                $model->status = Likes::LIKES_YES;
                $model->created_at = TIMESTAMP;
                if ($model->save() == false) {
                    throw new \Exception(array_values($model->getFirstError())[0]);
                }

                $posts = Posts::findOne($postsId);
                $posts->likes = $posts->likes + 1;
                if ($posts->save() == false) {
                    throw new \Exception(array_values($posts->getFirstError())[0]);
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
        }
    }
}
