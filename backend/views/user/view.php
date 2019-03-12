<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p style="text-align: right">
        <?= Html::a('返回列表', ['index', 'page' => $page], ['class' => 'btn btn-primary']) ?>
    </p>
    <p>
        <?= Html::a('修改信息', ['update', 'id' => $model->id , 'page' => $page], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id' ,
            'username' ,
            'nickname' ,
            'email',
            'phone',
            [
                'label' => '等级',
                'value' => $model->getRoleText($model->role)
            ],
            'avatar',
            [
                'label' => '创建时间',
                'value' =>  date('Y-m-d',$model->created_at),
            ]  ,
            [
                'label' => '更新时间',
                'value' =>  date('Y-m-d',$model->updated_at),
            ]  ,
        ],
    ]) ?>
</div>
