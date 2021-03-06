<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Posts;

$modelLabel = new \common\models\Reply();
?>
<?php $this->beginBlock('header'); ?>
<!-- <head></head>中代码块 -->
<?php $this->endBlock(); ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/index']) ?>"
                               class="btn btn-xs btn-primary">返回列表</a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <div class="tab-content">

                        <div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label">文章标题</label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= Posts::getPostsTitle($model->posts_id)?></div>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label">评论</label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $model->content ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="status"
                                   class="col-sm-2 control-label">昵称</label>
                            <div class="col-sm-8" style="width: 10%">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= \common\models\User::getUsername($model->user_id) ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="logo" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <div class="form-control" style="height: auto;min-height: 34px;border: none;">
                                    <a href="javascript:history.back(-1)" class="btn btn-primary"> 返&nbsp;回</a>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
