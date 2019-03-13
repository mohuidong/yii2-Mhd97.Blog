<?php

namespace backend\controllers;

use common\models\Common;
use Yii;
use yii\helpers\Json;
use common\models\Posts;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\models\PostClass;

/**
 * PostController implements the CRUD actions for AdminPost model.
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true ,
                        'roles' => ['postList'],
                    ],
                    [
                        'actions' => ['create','delete','update','delrecord'],
                        'allow' => true,
                        'roles' => ['postCud'],
                    ],
                    [
                        'actions' => ['change','status','score'],
                        'allow' => true,
                        'roles' => ['postChange'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Posts::find();
        $querys = Yii::$app->request->get('query');
        if (is_array($querys)) {
            if (count($querys) > 0) {
                if ($querys['title']) {
                    $query = $query->andWhere(['{{%post}}.title' => $querys['title']]);
                }
                if ($querys['class'] >= 0) {
                    $query = $query->andWhere(['{{%post}}.cat_id' => $querys['cat_id']]);
                }
                if ($querys['status'] >= 0) {
                    $query = $query->andWhere(['{{%post}}.status' => $querys['status']]);
                }
                if ($querys['b_time']) {
                    $querys['b_time'] = strtotime($querys['b_time']);
                    $query = $query->andWhere(['>=', '{{%post}}.created_at', $querys['b_time']]);
                }
                if ($querys['e_time']) {
                    $querys['e_time'] = strtotime($querys['e_time']);
                    $query = $query->andWhere(['<=', '{{%post}}.updated_at', $querys['e_time']]);
                }
            }
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_at desc')
            ->all();

        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
            'status' => Posts::$status,
//            'recommend' => $this->recommend,
            'class' => Posts::$class,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'status' => Posts::$status,
            'class' => Posts::$class,
        ]);

    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Posts();
        $class = PostClass::formatData(PostClass::find()->all());
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Posts::MANAGER_UID;
            $model->user_name = Posts::MANAGER_NAME;
            $model->status = intval($model->status);
            $model->title = Common::filter($model->title);
            $model->summary = Common::filter($model->summary,2);
            if($model->save()) {
                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'class' => $class,
                'status' => Posts::$status,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $class = PostClass::formatData(PostClass::find()->all());
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Posts::MANAGER_UID;
            $model->user_name = Posts::MANAGER_NAME;
            $model->status = intval($model->status);
            $model->title = Common::filter($model->title);
            $model->summary = Common::filter($model->summary,2);
            if($model->save()){
                return $this->redirect(['index']);
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'class' => $class,
                    'status' => Posts::$status,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'class' => $class,
                'status' => Posts::$status,
            ]);
        }
    }

    /**
     * ajax 单次删除
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (Posts::deleteAll(['in', 'id', Json::decode($id)])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }
        return $response;
    }

    /**
     * ajax 批量删除
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletes()
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (Posts::deleteAll(['in', 'id', Json::decode(Yii::$app->request->post('ids'))])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        } else {
            $response['message'] = '操作失败';
        }
        return $response;
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionStatus()
    {
        $id = Yii::$app->request->post('id');
        $status = Yii::$app->request->post('status');
        $model = $this->findModel($id);
        $model->status = $status;
        if($model->save(false)){
            return 100;
        }else{
            return 300;
        }
    }
}
