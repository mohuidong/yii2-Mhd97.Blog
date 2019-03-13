<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\xui\Datetimepicker;
use yii\xui\Ueditor;

$this->title = '角色权限配置';
$this->params['breadcrumbs'][] = $this->title;
$this->params['navtabs'] = [
	['label' => '用户设置', 'url' => ['/auth/users']],
	['label' => '角色管理', 'url' => ['/auth/roles'], 'active' => 1],
];
$this->params['breadcrumbs'][] = $this->title;
$this->params['route'] = '/dashboard/index';
?>

    <div class="box">
        <div class="box-header">
            <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                    <a id="create_btn" href="/auth/roles" class="btn btn-xs btn-primary">返回列表</a>
                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <!-- begin x-manage-title -->
            <div class="x-manage-title clearfix">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <!-- end x-manage-title -->

            <?= Html::beginForm(null, 'post', ['class' => 'form-horizontal', 'data-form-auth' => 'role']) ?>

            <!-- begin x-manage-box -->
            <div class="">
                <div class="title">
                    <h3>角色配置</h3>
                </div>
                <div>
                    <div class="form-group">
                        <?= Html::label('英文名称：', 'x-role-name', ['class' => 'col-sm-3 control-label']) ?>
                        <div class="col-sm-6"><?php if($role && $role->name == $root){ ?><p class="form-control-static"><?= $role->name ?><?= Html::hiddenInput('name', $role->name) ?></p><?php }else{ ?><?= Html::textInput('name', $role ? $role->name : \Yii::$app->session->getFlash('name'), ['class' => 'form-control', 'id' => 'x-role-name', 'required' => 'required', 'autofocus' => 'autofocus']) ?><? } ?></div>
                    </div>
                    <div class="form-group">
                        <?= Html::label('中文备注：', 'x-role-description', ['class' => 'col-sm-3 control-label']) ?>
                        <div class="col-sm-6"><?= Html::textInput('description', $role ? $role->description : \Yii::$app->session->getFlash('description'), ['class' => 'form-control', 'id' => 'x-role-description', 'required' => 'required']) ?></div>
                    </div>
                </div>
            </div>
            <!-- end x-manage-box -->

            <?php foreach(\Yii::$app->params['permissions'] as $gname => $group){ ?>
                <div class="J-x-auth-list">
                    <div class="table-bordered">
                    <div class="bg-info">
                        <h3><?= Html::checkbox(null, false, ['data-checkall' => $gname, 'label' => $group['name'], 'labelOptions' => ['class' => 'checkbox-inline']]) ?></h3>
                    </div>
                    <div class="">
                        <?php foreach($group['items'] as $name => $description){ ?>
                            <?= Html::checkbox('permissions[]', $role ? $auth->hasChild($role, $auth->getPermission($name)) : in_array($name, \Yii::$app->session->getFlash('permissions', [])), ['value' => $name, 'data-checkbox' => $gname, 'label' => $description, 'labelOptions' => ['class' => 'checkbox-inline']]) ?>
                        <? } ?>
                    </div>
                    </div>
                </div>
            <? } ?>

            <!-- begin x-manage-submit -->
            <div class="x-manage-submit x-manage-area text-center">
                <?= Html::submitButton('保存配置', ['class' => 'btn btn-primary btn-lg']) ?>
                <?= Html::a('返回', ['/auth/roles'], ['class' => 'btn btn-default btn-lg']) ?>
            </div>
            <!-- end x-manage-submit -->

            <?= Html::endForm() ?>
        </div>
        <!-- /.box-body -->
    </div>

