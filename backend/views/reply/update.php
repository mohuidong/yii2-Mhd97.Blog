<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Post;

$modelLabel = new \common\models\Question()
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
                            <label for="user_id" style="padding-top: 0"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("user_id") ?></label>
                            <div class="col-sm-8">
                                <div><?=$model->member['username']?></div>

                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="form-group">
                            <label for="title"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("answer") ?></label>
                            <div class="col-sm-8">
                                <?php echo $form->field($model, 'answer')->textInput(["class" => "form-control", "placeholder" => $modelLabel->getAttributeLabel("answer"), "id" => 'answer']) ?>
                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="status"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("status") ?></label>
                            <div class="col-sm-8" style="width: 10%">
                                <?php echo $form->field($model, 'status')->inline()->dropDownList($status) ?>
                            </div>
                            <label for="recommend"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("recommend") ?></label>
                            <div class="col-sm-8" style="width: 10%">
                                <?php echo $form->field($model, 'recommend')->inline()->dropDownList($recommend) ?>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="form-group">
                            <label for="content"
                                   class="col-sm-2 control-label"><?php echo $modelLabel->getAttributeLabel("content") ?></label>
                            <div class="col-sm-8">
                                <textarea name="AdminPost[content]" id="content" style="width: 100%;height: 300px;float: left;"><?=$model->content?></textarea>
                                <!--<script type="text/javascript">-->
                                    <!--UE.getEditor("content", {-->
                                        <!--theme: "default", //皮肤-->
                                        <!--lang: "zh-cn",    //语言-->
                                        <!--wordCount: true,-->
                                        <!--maximumWords: 1000,-->
                                    <!--});-->

                                <!--</script>-->

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

<?php $this->beginBlock('footer'); ?>
<?php $this->endBlock(); ?>
<script>
    window.onload = function () {
        UE.getEditor("content", {
            theme: "default", //皮肤
            lang: "zh-cn",    //语言
            wordCount: true,
            maximumWords: 1000,
        });
        var type = '';
        $(".change_img").click(function(){
            type = $(this).attr('type');
            $("#hiddenFile").click();
        })
        window.ajaxFileUpload = function() {
            layer.load(2);
            $.ajaxFileUpload
            (
                {
                    url: "<?=Url::toRoute(['public/file'])?>", //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: 'hiddenFile', //文件上传域的ID
                    dataType: 'JSON', //返回值类型 一般设置为json
                    success: function (data)  //服务器成功响应处理函数
                    {
                        layer.closeAll();
                        var obj = jQuery.parseJSON(data);
                        if (obj.status != 200) {
                            layer.alert('上传失败', {icon: 2});
                            return;
                        } else {
                            $('.' + type).attr('src', obj.path);
                            $('#' + type).val(obj.path);
                        }
                    }
                }
            );
            return false;
        }
    }


    // var type = '';
    // $(".change_img").click(function(){
    //     type = $(this).attr('type');
    //     $("#hiddenFile").click();
    // })
    // function ajaxFileUpload() {
    //     layer.load(2);
    //     $.ajaxFileUpload
    //     (
    //         {
    //             url: "<?=Url::toRoute(['public/file'])?>", //用于文件上传的服务器端请求地址
    //             secureuri: false, //是否需要安全协议，一般设置为false
    //             fileElementId: 'hiddenFile', //文件上传域的ID
    //             dataType: 'JSON', //返回值类型 一般设置为json
    //             success: function (data)  //服务器成功响应处理函数
    //             {
    //                 layer.closeAll();
    //                 var obj = jQuery.parseJSON(data);
    //                 if (obj.status != 200) {
    //                     layer.alert('上传失败', {icon: 2});
    //                     return;
    //                 } else {
    //                     $('.' + type).attr('src', obj.path);
    //                     $('#' + type).val(obj.path);
    //                 }
    //             }
    //         }
    //     );
    //     return false;
    // }
</script>
