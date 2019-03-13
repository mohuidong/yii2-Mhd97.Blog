<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Content;

$modelLabel = new \common\models\PostClass()
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
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => '<div class="span12 field-box">{input}</div>{error}',
                        ],
                        'options' => [
                            'class' => 'new_user_form inline-input',
                        ],
                        'id' => 'form',
                    ])
                    ?>
                    <div class="tab-content">

                        <div class="form-group">
                            <label for="content_name"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("class_name") ?></label>
                            <div class="col-sm-8" style="width: 20%">
                                <?php echo $form->field($model, 'class_name')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("class_name"), "id" => 'class_name']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="order"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("order") ?></label>
                            <div class="col-sm-8" style="width: 10%">
                                <?php echo $form->field($model, 'order')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("order"), "id" => 'order']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group" style="display: none">
                            <label for="create_time"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("create_time") ?></label>
                            <div class="col-sm-8" >
                                <?php echo $form->field($model, 'create_time')->textInput(['value'=>time(),"class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("create_time"), "id" => 'create_time']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?php echo Html::Button('保存', ['class' => "btn btn-primary",'id'=>'put_up']); ?>
                                <span>&nbsp;</span>
                                <?php echo Html::resetButton('重置', ['class' => "btn btn-primary"]); ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php ActiveForm::end(); ?>
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
<script>
    window.onload = function(){
        $("#put_up").click(function(){
            var class_name = $("#class_name").val();
            if(!class_name){
                $("#content_name").focus();
                layer.tips('分类名称不能为空','#class_name',{tips:2})
            }else{
                $("#form").submit();
            }
        })
    }
</script>
