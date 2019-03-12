<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\GoodsClass;

/* @var $this yii\web\View */
/* @var $model common\models\FinanceProduct */

$this->title = $model->name;
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
            'pid',
            'name' ,
            'email',
            [
                'label' => '性别',
                'value' => $model->sex == 1? '男' : '女'
            ],
            'face',
            'nickname',
            'id_card',
            'commission',
            'balance',
            [
                'label' => '实名状态',
                'value' => $model->authentication== 1 ? "是" : "否",
            ] ,
            'agent_at',
            'commission_goods',
            'commission_agent',
            'subsidy',
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
