<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use common\models\Posts;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * PostController implements the CRUD actions for AdminPost model.
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */

    public $status = ['10'=>'正在审核','11'=>'审核成功','12'=>'审核失败'];

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
            ->orderBy('create_time desc')
            ->all();

        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
            'status' => $this->status,
            'recommend' => $this->recommend,
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
            'status' => $this->status,
            'recommend' => $this->recommend,
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
            $uid = Yii::$app->request->post('AdminPost')['user_id'];
            $model->status = intval($model->status);
            $model->title = Common::filter($model->title);
            $model->content = Common::filter($model->content,2);
            $model->recommend = intval($model->recommend);
            $model->create_time = time();
            if($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'class' => $class,
                'status' => $this->status,
                'recommend' => $this->recommend,
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
            $model->status = intval($model->status);
            $model->title = Common::filter($model->title);
            $model->content = Common::filter($model->content,2);
            $model->recommend = intval($model->recommend);
            $model->create_time = time();
            if($model->save()){
                return $this->redirect(['index']);
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'class' => $class,
                    'status' => $this->status,
                    'recommend' => $this->recommend,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'class' => $class,
                'status' => $this->status,
                'recommend' => $this->recommend,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDelrecord(array $ids)
    {
        if (count($ids) > 0) {
            $c = Post::deleteAll(['in', 'id', $ids]);
            echo json_encode(array('errno' => 0, 'data' => $c, 'msg' => json_encode($ids)));
        } else {
            echo json_encode(array('errno' => 2, 'msg' => ''));
        }
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
        if (($model = Post::findOne($id)) !== null) {
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

    public function actionChange($id)
    {
        Yii::$app->response->format = 'json';
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        $model = $this->findModel($id);
        $model->recommend = ($model->recommend == Post::RECOMMEND_YES ? Post::RECOMMEND_NO : Post::RECOMMEND_YES);
        if ($model->save(false)) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }
        return $response;
    }

}
