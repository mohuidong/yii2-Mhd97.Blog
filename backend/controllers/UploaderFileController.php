<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use common\service\UploadForm;
/**
 * UploadedFile controller
 */
class UploaderFileController extends Controller
{
    public $enableCsrfValidation=false;

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'editor'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new UploadForm();

        $response = ['code' => 1, 'msg' => '上传失败'];
        Yii::$app->response->format = 'json';

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstanceByName('file');
            $model->folder = Yii::$app->request->post('folder');
            if ($model->upload()) {
                $response = ['code' => 0, 'url' => Yii::$app->params['domain'] . $model->getObject(), 'attachment' => $model->getObject()];
                return $response;
            } else {
                $response = ['code' => 1, 'msg' => array_values($model->getFirstErrors())[0]];
                return $response;
            }
        }

        return $response;
    }

    public function actionEditor() {

        $model = new UploadForm();

        $response = [];
        Yii::$app->response->format = 'json';

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstanceByName('file');
            $model->folder = 'editor';

            if ($model->upload()) {
                $response = ['link' => Yii::$app->params['domain'] . $model->getObject()];
                return $response;
            }
        }

        return $response;
    }

}
