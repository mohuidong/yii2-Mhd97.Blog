<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Answer;
use kartik\datetime\DateTimePicker;
$modelLabel = new \common\models\Answer()
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
<!--                            <a id="create_btn" href="--><?//= Url::toRoute([$this->context->id . '/create']) ?><!--"-->
<!--                               class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>-->
                            &nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php ActiveForm::begin(['id' => 'search-form', 'method' => 'get', 'options' => ['class' => 'form-inline'], 'action' => '']); ?>
                                    <div class="col-lg-2 mar-10">
                                        <div class="input-group" style="margin: 5px;">
                                            <span class="input-group-addon" id="basic-addon1">问题id:</span>
                                            <input type="text" class="form-control" id="query[question_id]" name="query[question_id]" value="<?= isset($query["question_id"]) ? $query["question_id"] : "" ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 mar-10">
                                        <div class="input-group" style="margin: 5px;">
                                            <span class="input-group-addon" id="basic-addon1">用户id:</span>
                                            <input type="text" class="form-control" id="query[user_id]" name="query[user_id]" value="<?= isset($query["user_id"]) ? $query["user_id"] : "" ?>">
                                        </div>
                                    </div>

                                    <div class="col-lg-2 mar-10">
                                        <div class="input-group" style="margin: 5px;">
                                            <span class="input-group-addon" id="basic-addon1">关键词:</span>
                                            <input type="text" class="form-control" id="query[answer]" name="query[answer]" value="<?= isset($query["answer"]) ? $query["answer"] : "" ?>">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="glyphicon glyphicon-zoom-in icon-white"></i>搜索
                                        </button>

                                        <a class="btn btn-primary btn-sm"
                                           href="<?= Url::toRoute([$this->context->id . '/' . Yii::$app->controller->action->id]) ?>">
                                            <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <hr/>
                            <div class="col-sm-12">
                                <button
                                        data-text="是否确定要删除？"
                                        data-url="<?=Url::toRoute([$this->context->id.'/deletes'])?>"
                                        class="J-q-confirm btn btn-xs btn-danger q-checkbox-father"
                                        data-batch="1"
                                        data-batch-key="ids"
                                        data-batch-class="q-checkbox"
                                        data-type="post"
                                >批量删除</button>
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th><input id="data_table_check" type="checkbox"></th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">回复id</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">问题id</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">评论</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">发布者</th>
                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">创建时间</th>


                                        <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1"
                                            aria-sort="ascending">操作
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($model as $list) {
                                        ?>
                                        <tr id="rowid_$list->id">
                                            <td><label><input type="checkbox" class="q-checkbox" value="<?= $list->id ?>"></label></td>
                                            <td><?= $list->id ?></td>
                                            <td><?= $list->question_id ?></td>
                                            <td><?= $list->answer?></td>
                                            <td><?= \common\models\User::getUsername($list->user_id)?></td>
                                            <td><?= date("Y-m-d H:i:s", $list->created_at) ?></td>

                                            <td class="center">
                                                <a id="view_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/view', 'id' => $list->id]) ?>">
                                                    <i class="glyphicon glyphicon-zoom-in icon-white"></i>查看</a>
                                                <a id="edit_btn" class="btn btn-primary btn-sm"
                                                   href="<?= Url::toRoute([$this->context->id . '/update', 'id' => $list->id]) ?>">
                                                    <i class="glyphicon glyphicon-edit icon-white"></i>修改</a>
                                                <button class="J-q-confirm btn btn-danger btn-sm"
                                                        data-url="<?=Url::toRoute([$this->context->id.'/delete', 'id'=>$list['id']])?>"
                                                        data-text="是否删除回复:<?=$list['answer']?>?"
                                                        data-type="get">
                                                    <i class="glyphicon glyphicon-trash icon-white"></i>删除</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- row end -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="dataTables_info" id="data_table_info" role="status" aria-live="polite">
                                    <div class="infos">
                                        从<?= $pages->getPage() * $pages->getPageSize() + 1 ?>
                                        到 <?= ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ? $pageCount : $pages->totalCount ?>
                                        共 <?= $pages->totalCount ?> 条记录
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate"
                                     style="text-align: right;padding-right: 50px;">
                                    <?= LinkPager::widget([
                                        'pagination' => $pages,
                                        'nextPageLabel' => '下一页',
                                        'prevPageLabel' => '上一页',
                                        'firstPageLabel' => '首页',
                                        'lastPageLabel' => '尾页',
                                    ]); ?>

                                </div>
                            </div>
                        </div>
                        <!-- row end -->
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
<script>

    function initModel(id, type, fun) {
        $.ajax({
            type: "GET",
            url: "<?= Url::toRoute($this->context->id . '/view')?>",
            data: {"id": id},
            cache: false,
            dataType: "json",
            error: function (xmlHttpRequest, textStatus, errorThrown) {
                alert("出错了，" + textStatus);
            },
            success: function (data) {
                initEditSystemModule(data, type);
            }
        });
    }
    function editAction(id) {
        initModel(id, 'edit');
    }

    function deleteAction(id) {
        var ids = [];
        if (!!id == true) {
            ids[0] = id;
        }
        else {
            var checkboxs = $('#data_table tbody :checked');
            if (checkboxs.size() > 0) {
                var c = 0;
                for (i = 0; i < checkboxs.size(); i++) {
                    var id = checkboxs.eq(i).val();
                    if (id != "") {
                        ids[c++] = id;
                    }
                }
            }
        }
        if (ids.length > 0) {
            admin_tool.confirm('请确认是否删除', function () {
                $.ajax({
                    type: "GET",
                    url: "<?=Url::toRoute($this->context->id . '/delrecord')?>",
                    data: {"ids": ids},
                    cache: false,
                    dataType: "json",
                    error: function (xmlHttpRequest, textStatus, errorThrown) {
                        alert("出错了，" + textStatus);
                    },
                    success: function (data) {
                        for (i = 0; i < ids.length; i++) {
                            $('#rowid_' + ids[i]).remove();
                        }
                        admin_tool.alert('msg_info', '删除成功', 'success');
                        window.location.reload();
                    }
                });
            });
        }
        else {
            admin_tool.alert('msg_info', '请先选择要删除的数据', 'warning');
        }

    }

    function getSelectedIdValues(formId) {
        var value = "";
        $(formId + " :checked").each(function (i) {
            if (!this.checked) {
                return true;
            }
            value += this.value;
            if (i != $("input[name='id']").size() - 1) {
                value += ",";
            }
        });
        return value;
    }

    $('#edit_dialog_ok').click(function (e) {
        e.preventDefault();
        $('#module-form').submit();
    });

    /* $('#create_btn').click(function (e) {
     e.preventDefault();
     initEditSystemModule({}, 'create');
     });*/

    $('#delete_btn').click(function (e) {
        e.preventDefault();
        deleteAction('');
    });

</script>
<?php $this->endBlock(); ?>
