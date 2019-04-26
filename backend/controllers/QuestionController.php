<?php

namespace backend\controllers;

use common\models\Common;
use Yii;
use yii\helpers\Json;
use common\models\Question;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\models\Answer;

/**
 * PostController implements the CRUD actions for AdminPost model.
 */
class QuestionController extends Controller
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
                        'roles' => ['questionList'],
                    ],
                    [
                        'actions' => ['create','update','delete','deletes','status'],
                        'allow' => true,
                        'roles' => ['questionCud'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
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
        $query = Question::find();
        $querys = Yii::$app->request->get('query');
        if (is_array($querys)) {
            if (count($querys) > 0) {
                if ($querys['question']) {
                    $query = $query->andWhere(['{{%question}}.question' => $querys['question']]);
                }
                if ($querys['status'] >= 0) {
                    $query = $query->andWhere(['{{%question}}.status' => $querys['status']]);
                }
                if ($querys['b_time']) {
                    $querys['b_time'] = strtotime($querys['b_time']);
                    $query = $query->andWhere(['>=', '{{%question}}.created_at', $querys['b_time']]);
                }
                if ($querys['e_time']) {
                    $querys['e_time'] = strtotime($querys['e_time']);
                    $query = $query->andWhere(['<=', '{{%question}}.updated_at', $querys['e_time']]);
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
            'status' => Question::$status,
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
            'status' => Question::$status,
        ]);

    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Question();
        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Question::MANAGER_UID;
            $model->status = intval($model->status);
            $model->question = Common::filter($model->question);
            $model->content = Common::filter($model->content,2);
            if($model->save()) {
                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'status' => Question::$status,
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
        if ($model->load(Yii::$app->request->post())) {
            $model->status = intval($model->status);
            $model->question = Common::filter($model->question);
            $model->content = Common::filter($model->content,2);
            if($model->save()){
                return $this->redirect(['index']);
            }else{
                return $this->render('create', [
                    'model' => $model,
                    'status' => Question::$status,
                ]);
            }

        } else {
            return $this->render('create', [
                'model' => $model,
                'status' => Question::$status,
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
        if (Question::deleteAll(['in', 'id', Json::decode($id)])) {
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
        if (Question::deleteAll(['in', 'id', Json::decode(Yii::$app->request->post('ids'))])) {
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
        if (($model = Question::findOne($id)) !== null) {
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
