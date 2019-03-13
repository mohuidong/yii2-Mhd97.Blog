<?php

namespace backend\controllers;
use common\models\User;
use yii\data\Pagination;
use yii\web\Controller;
use yii\filters\AccessControl;
use Yii;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'tree', 'tree-json'],
                        'allow' => true,
                        'roles' => ['userList'],
                    ],
                    [
                        'actions' => ['create', 'delete', 'update', 'deletes'],
                        'allow' => true,
                        'roles' => ['userCud'],
                    ],
                ]
            ],
        ];
    }

    public function actionIndex($key = null, $value = null, $role = null, $startTime = null, $endTime = null)
    {
        $query = User::find();

        if ($value) {
            switch ($key) {
                case 'id':
                    $query->andWhere(['id' => $value]);
                    break;
                case 'phone':
                    $query->andWhere(['like', 'phone', $value]);
                    break;
                case 'username':
                    $query->andWhere(['like', 'username', $value]);
                    break;
            }
        }
        if ($role > 0) {
            $query->andWhere(['role' => $role]);
        }

        if ($startTime){
            $startTime = strtotime($startTime);
            $query->andWhere(['>=', "{{%user}}.created_at", $startTime]);
        }

        if ($endTime){
            $endTime = strtotime($endTime);

            $query->andWhere(['<=', "{{%user}}.created_at", $endTime]);
        }

        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $models = $query
            ->offset($pagination->offset)
            ->orderBy('id DESC')
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pagination,
            'key' => $key,
            'value' => $value,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'role' => $role,
        ]);
    }

    public function actionView($id, $page)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'page' => $page,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = Yii::$app->request->post('User');
            if (!empty($post['password_hash'])) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($post['password_hash']);
            }
            $model->generateAuthKey();
            $model->generatePasswordResetToken();
            if ($model->save()) {
                $this->redirect('index');
            }
        }
        return $this->render('create',[
            'model' => $model,
        ]);
    }

    public function actionUpdate($id, $page)
    {
        $model = $this->findModel($id);
        $oldPass = $model->password_hash;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('User');
            if (!empty($post['password_hash'])) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($post['password_hash']);
            } else {
                $model->password_hash = $oldPass;
            }

            if($model->save()){
                $this->redirect(['index']);
            }
        }
        return $this->render('create',[
            'model' => $model,
            'page' => $page,
        ]);
    }

    public function actionDelete($id)
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (User::deleteAll(['in', 'id', Json::decode($id)])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }
        return $response;
    }

    public function actionDeletes(){
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (User::deleteAll(['in', 'id', Json::decode(Yii::$app->request->post('ids'))])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }
        return $response;
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
