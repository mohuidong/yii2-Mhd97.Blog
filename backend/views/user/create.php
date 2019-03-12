<?php

use yii\widgets\ActiveForm;
//use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = '编辑用户信息' ;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = '编辑用户信息';

?>

<section class="content" style="">
    <div class="row" style="">
        <div class="col-xs-12 " style="">
            <div class="box" style="">


                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <?php if (isset($isUpdate)):?>
                                <a id="create_btn" href="<?=Url::to('/user/index?page=' . $page)?>" class="btn btn-xs btn-primary">返回列表</a>
                            <?php else:?>
                                <a id="create_btn" href="<?=Url::to('/user/index')?>" class="btn btn-xs btn-primary">返回列表</a>
                            <?php endif;?>

                        </div>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body" style="">
                    <?php $form = ActiveForm::begin()?>


                        <div class="tab-content" style="">

                            <div class="form-group">
                                <label class="col-sm-2 control-label">手机号<span style="color: red">*</span></label>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <?=$form->field($model, 'phone')->textInput(['class' => 'form-control', 'placeholder' => '手机号码'])->label(false)?>
                                    </div>
                                </div>
                                <label class="col-sm-2 control-label">昵称</label>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <?=$form->field($model, 'nickname')->textInput(['class' => 'form-control', 'placeholder' => '昵称'])->label(false)?>
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>


                            <div class="form-group" >
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <input class="form-control" name="User[password_hash]" value="" placeholder="不填则不修改登录密码">
                                    </div>
                                </div>
                                <label class="col-sm-2 control-label">密码</label>
                                <div class="col-sm-3">
                                    <div class="form-group ">
                                        <input class="form-control" name="User[password_hash]" value="" placeholder="不填则不修改登录密码">
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label" >昵称</label>
                                <div class="col-sm-3">
                                    <?=$form->field($model, 'nickname')->textInput(['class' => 'form-control', 'placeholder' => '昵称'])->label(false)?>
                                </div>
                                <div class="clear"></div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label" >Email</label>
                                <div class="col-sm-3" >
                                    <?=$form->field($model, 'email')->textInput(['type'=> 'email','class' => 'form-control', 'placeholder' => '邮箱'])->label(false)?>
                                </div>
                            <div class="clear"></div>

                            <div class="form-group">
                                <label for="avatar"
                                       class="col-sm-2 control-label">头像</label>
                                <div class="col-sm-8">
                                    <div id="wrapper">
                                        <?= $form->field($model, 'avatar')->widget('manks\FileInput', [
                                            'clientOptions' => [
                                                'formData' => [
                                                    'folder' => 'users' // 上传的文件夹
                                                ]
                                            ]
                                        ])->label(false); ?>
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>


                            <div class="form-group ">
                                <label  class="col-sm-2 control-label">等级</label>
                                <div class="col-sm-3">
                                    <?=$form->field($model, 'role')->dropDownList(User::$roles)->label(false)?>
                                </div>
                                <label  class="col-sm-2 control-label">状态</label>
                                <div class="col-sm-3">
                                    <?=$form->field($model, 'status')->dropDownList([
                                        User::STATUS_NORMAL => '正常',
                                        User::STATUS_BAN => '封号',
                                    ])->label(false)?>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">&nbsp;</label>
                                <div class="col-sm-8">
                                    <button type="submit" class="btn btn-success">保存</button>
                                    <span>&nbsp;</span>
                                    <button type="reset" class="btn btn-primary">重置</button>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php ActiveForm::end()?>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

