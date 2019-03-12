<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Manager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content" style="background-color: #ffffff">
    <div class="manager-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('添加管理员', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'ID',
                    'attribute' => 'id',
                ],
                [
                    'label' => '用户名',
                    'attribute' => 'username',
                ],
                [
                    'label' => '手机号',
                    'attribute' => 'phone',
                ],
                [
                    'label' => '真实姓名',
                    'attribute' => 'name',
                ],
                [
                    'label' => '状态',
                    'attribute' => 'status',
                    'value' => function($model) {
                        return ($model->status == Manager::STATUS_ACTIVE ? '活跃的' : '禁止');
                    }
                ],
                [
                    'label'=>'创建时间',
                    'attribute' => 'created_at',
                    'value' => function($model) {
                        return date('Y-m-d H:i', $model->created_at);
                    }
                ],

                ['class' => 'yii\grid\ActionColumn'],
        ]]); ?>
    </div>

