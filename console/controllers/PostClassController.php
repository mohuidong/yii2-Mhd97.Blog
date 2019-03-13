<?php

namespace console\controllers;

use common\models\PostClass;
use common\models\Posts;
use Yii;
use yii\console\Controller;

class PostClassController extends Controller{

	public function actionIndex(){
	    $postClass = Posts::$class;
	    foreach ($postClass as $key => $v) {
            $model = new PostClass();
            $model->id = $key;
            $model->class_name = $v;
            $model->order = $key;
            if ($model->save(false)) {
                echo '迁移文章分类（'. $model->class_name .'） 成功';
                echo "\n";
            } else {
                echo '迁移文章分类（'. $model->class_name .'） 失败！！！';
                echo "\n";
            }
        }
	}
}
