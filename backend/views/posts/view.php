<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Post;

$modelLabel = new \common\models\Posts();
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
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("title") ?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $model->title ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="summary"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("summary") ?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $model->summary ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <!--<div class="form-group">
                            <label for="code"
                                   class="col-sm-2 control-label"><?php /*echo $modelLabel->getAttributeLabel("code") */?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?/*= $model->code */?></div>
                            </div>
                        </div>
                        <div class="clear"></div>-->
                        <!--<div class="form-group">
                            <label for="class"
                                   class="col-sm-2 control-label"><?php /*echo $modelLabel->getAttributeLabel("class") */?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?/*= $model->class */?></div>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="status"
                                   class="col-sm-2 control-label">文章状态</label>
                            <div class="col-sm-8" style="width: 10%">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $status[$model->status] ?></div>
                            </div>
                            <label for="status"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("cat_id") ?></label>
                            <div class="col-sm-8" style="width: 10%">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $class[$model->cat_id] ?></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="label_img"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("label_img") ?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;border: none"><img src="<?= $model->fullAddress ?>" style="width: 110px;height: 95px"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="content"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("content") ?></label>
                            <div class="col-sm-8">
                                <div class="form-control"
                                     style="height: auto;min-height: 34px;"><?= $model->content ?></div>
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
