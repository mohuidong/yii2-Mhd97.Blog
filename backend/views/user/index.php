<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;

$modelLabel = new \common\models\User();
$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.5/themes/default/style.min.css" />
<?php $this->registerJsFile("//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.5/jstree.min.js", ['depends'=>  'backend\assets\AppAsset']);?>
<!-- Main content -->

<div class="row">
    <div class="col-xs-12">
        <div class="box">

            <div class="box-header">
                <h3 class="box-title">用户管理</h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <a id="create_btn" href="<?= Url::toRoute([$this->context->id . '/create']) ?>"
                           class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a>
                        <!--                <button id="create_btn" type="button" class="btn btn-xs btn-primary">添&nbsp;&emsp;加</a></button>-->
                    </div>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <!-- row start search-->
                    <div class="row">

                        <div class="col-sm-12">
                            <?php ActiveForm::begin(['id' => 'user-search-form', 'method'=>'get', 'options' => ['class' => 'form-inline'], 'action'=>Url::toRoute('user/index')]); ?>
                            <div class="input-group" style="margin: 5px;">
                                <select name="key" style="height:34px;border: 1px solid #ccc;padding: 5px;margin-right: 20px;">
                                    <option value="0">全部</option>
                                    <option value="id" <?php if($key == 'id'):?>selected<?php endif;?>><?=$modelLabel->getAttributeLabel('id')?></option>
                                    <option value="phone" <?php if($key == 'phone'):?>selected<?php endif;?>><?=$modelLabel->getAttributeLabel('phone')?></option>
                                    <option value="username" <?php if($key == 'username'):?>selected<?php endif;?>><?=$modelLabel->getAttributeLabel('username')?></option>
                                </select>

                            </div>
                            <input type="text" class="form-control" name="value" value="<?=$value?>">

                            <div class="input-group" style="margin: 5px;">
                                <label for="">类型:</label>
                                <select name="role" style="height:34px;border: 1px solid #ccc;padding: 5px;margin-right: 20px;">
                                    <option value="0">请选择类型</option>
                                    <?php foreach (User::$roles as $k => $v):?>
                                        <option value="<?=$k?>" <?php if($role == $k):?>selected<?php endif;?>><?=$v?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group" style="margin: 5px;">
                                <?php
                                echo '<label>创建时间:</label>';
                                echo DateTimePicker::widget([
                                    'name' => 'startTime',
                                    'options' => ['placeholder' => ''],
                                    //注意，该方法更新的时候你需要指定value值
                                    'value' => !empty($query['startTime']) ? date('Y-m-d H:i:s', $query['startTime'] ) : "",
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd hh:ii:ss',
                                        'todayHighlight' => true
                                    ]
                                ]);
                                echo '<label>至:</label>';
                                echo DateTimePicker::widget([
                                    'name' => 'endTime',
                                    'options' => ['placeholder' => ''],
                                    //注意，该方法更新的时候你需要指定value值
                                    'value' => !empty($query['endTime']) ? date('Y-m-d H:i:s', $query['endTime'] ) : "",
                                    'pluginOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd hh:ii:ss',
                                        'todayHighlight' => true
                                    ]
                                ])
                                ;?>
                            </div>
                            <div class="form-group">
                                <a onclick="document.getElementById('user-search-form').submit();return false;" class="btn btn-primary btn-sm" href="#"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>搜索</a>
                                <a class="btn btn-primary btn-sm" href="<?= Url::toRoute([$this->context->id . '/index'])?>"> <i class="glyphicon glyphicon-zoom-in icon-white"></i>清空</a>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <!-- row end search -->

                    <!-- row start -->
                    <div class="row">
                        <div class="col-sm-12">
                            <button
                                    type="button"
                                    class="btn btn-xs btn-danger J-q-confirm q-checkbox-father"
                                    data-text="是否确定要执行该操作？"
                                    data-url="/user/deletes"
                                    data-type="post"
                                    data-batch="1"
                                    data-batch-key="ids"
                                    data-batch-class="q-checkbox"
                            >批量删除</button>
                            <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                <thead>
                                <tr role="row">

                                    <?php
                                    echo '<th><input id="data_table_check" type="checkbox"></th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('id').'</th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('phone').'</th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('username').'</th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >状态</th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >等级</th>';
                                    echo '<th class="sorting" tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >'.$modelLabel->getAttributeLabel('created_at').'</th>';
                                    ?>

                                    <th tabindex="0" aria-controls="data_table" rowspan="1" colspan="1" aria-sort="ascending" >操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                foreach ($models as $model) {
                                    echo '<tr id="rowid_' . $model->id . '">';
                                    echo '  <td><label><input type="checkbox"  class="q-checkbox" value="' . $model->id . '"></label></td>';
                                    echo '  <td>' . $model->id . '</td>';
                                    echo '  <td>' . $model->phone . '</td>';
                                    echo '  <td>' . $model->username . '</td>';
                                    echo '  <td>' . ($model->status == 10 ? "正常" : "封号" ) . '</td>';
                                    echo '  <td>' . $model->getRoleText($model->role) . '</td>';
                                    echo '  <td>' . date('Y-m-d H:i:s',$model->created_at) . '</td>';
                                    echo '  <td class="center">';
                                    echo "      <a id='view_btn'  class='btn btn-primary btn-sm' href=".Url::toRoute([$this->context->id . '/view', 'id' => $model->id , 'page' => $pages->getPage() + 1])." > <i class='glyphicon glyphicon-zoom-in icon-white'></i>查看</a>";
                                    echo "      <a id='edit_btn'  class='btn btn-primary btn-sm' href=".Url::toRoute([$this->context->id . '/update', 'id' => $model->id , 'page' => $pages->getPage() + 1])." > <i class='glyphicon glyphicon-edit icon-white'></i>修改</a>";
                                    echo '  </td>';
                                    echo '<tr/>';
                                }
                                ?>

                                </tbody>
                                <!-- <tfoot></tfoot> -->
                            </table>
                        </div>
                    </div>
                    <!-- row end -->

                    <!-- row start -->
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="data_table_info" role="status" aria-live="polite">
                                <div class="infos">
                                    从<?= $pages->getPage() * $pages->getPageSize() + 1 ?>  到 <?= ($pageCount = ($pages->getPage() + 1) * $pages->getPageSize()) < $pages->totalCount ?  $pageCount : $pages->totalCount?>            		 共 <?= $pages->totalCount?> 条记录</div>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate">
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

<!-- /.content -->

<?php $this->beginBlock('footer');  ?>
<!-- <body></body>后代码块 -->

<script>
    function searchAction(){
        $('#user-search-form').submit();
    }
    function getMyDate(str){
        str=str*1000;
        var oDate = new Date(str),
            oYear = oDate.getFullYear(),
            oMonth = oDate.getMonth()+1,
            oDay = oDate.getDate(),
            oHour = oDate.getHours(),
            oMin = oDate.getMinutes(),
            oSen = oDate.getSeconds(),
            oTime = oYear +'-'+ getzf(oMonth) +'-'+ getzf(oDay) +' '+ getzf(oHour) +':'+ getzf(oMin) +':'+getzf(oSen);//最后拼接时间
        return oTime;
    };
    //补0操作
    function getzf(num){
        if(parseInt(num) < 10){
            num = '0'+num;
        }
        return num;
    }

</script>

<?php $this->endBlock(); ?>
