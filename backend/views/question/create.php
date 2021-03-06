<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Post;

$modelLabel = new \common\models\Posts()
?>

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
                            <label for="title"
                                   class="col-sm-2 control-label">问题<span style="color: red">*</span></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'question')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("question"), "id" => 'question']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="status"
                                   class="col-sm-2 control-label">状态</label>

                            <div class="col-sm-8" style="width: 10%">
                                <?php echo $form->field($model, 'status')->inline()->dropDownList($status) ?>
                            </div>

                            <!--<script type="text/javascript">-->
                                    <!--function changeid(){-->
                                       <!--var user_id = $("#user_id").val();-->
                                       <!--if(user_id==''){-->
                                            <!--$("#user_id").val(0);-->
                                       <!--}-->
                                    <!--}-->

                            <!--</script>-->
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="content"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("content") ?></label>
                            <div class="col-sm-8">
                                <?= froala\froalaeditor\FroalaEditorWidget::widget([
                                    'model' => $model,
                                    'attribute' => 'content',
                                    'options' => [
                                        // html attributes
                                        'id'=>'content',
                                    ],
                                    'clientOptions' => [
                                        'toolbarInline' => false,
                                        'theme' => 'royal', //optional: dark, red, gray, royal
                                        'language' => 'zh_cn', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                        'height' => 300,
                                        'imageUploadParam' => 'file',
                                        'imageUploadURL' => \yii\helpers\Url::to(['uploader-file/editor'])
                                    ]
                                ]); ?>

                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="resource" class="col-sm-2 control-label">&nbsp;</label>
                            <div class="col-sm-8">
                                <?php echo Html::submitButton('保存', ['class' => "btn btn-primary"]); ?>
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
<script>
    window.onload = function(){
        window.changeid = function(){
            var user_id = $("#user_id").val();
            if(user_id==''){
                $("#user_id").val(0);
            }
        }
        $(document).ready(function(){
            $("#adminpost-status").find("option[value='2']").attr("selected",true);
        });
    }
</script>
