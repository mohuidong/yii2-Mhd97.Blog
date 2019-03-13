<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = '用户设置';
$this->params['breadcrumbs'][] = $this->title;
$this->params['navtabs'] = [
	['label' => '用户设置', 'url' => ['/auth/users'], 'active' => 1],
	['label' => '角色管理', 'url' => ['/auth/roles']],
];
$this->params['route'] = '/dashboard/index';
?>
<?php $this->beginBlock('footer') ?>
<?= Html::jsFile('@web/js/auth.js') ?>
<?php $this->endBlock() ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <?= Html::a('添加管理用户', 'javascript:;', ['class' => 'btn btn-primary', 'data-auth-user' => 'add']) ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="javascript:;">用户设置</a></li>
                        <li><?= Html::a('角色管理', ['/auth/roles']) ?></li>
                    </ul>
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th>用户名</th>
                                        <th>手机</th>
                                        <th>邮箱</th>
                                        <th>所属角色</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($assigns as $assign){ ?>
                                        <tr>
                                            <td><?= $assign['user']['username'] ?></td>
                                            <td><?= $assign['user']['username'] ?></td>
                                            <td><?= $assign['user']['email'] ?></td>
                                            <td><?= $assign['item']['description'] ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="<?=Url::to(['/auth/user-role', 'uid' => $assign['user_id']])?>" data-auth-user="edit" data-revoke = "<?=$assign['item_name']?>"> <i class="glyphicon glyphicon-edit icon-white"></i>编辑</a>
                                                <a class="btn btn-danger btn-sm J-q-confirm" data-url="<?=Url::to(['/auth/revoke', 'uid' => $assign['user_id'], 'id' => $assign['item_name']])?>" data-text="确定要撤销此用户的管理权限吗?"> <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                                </td>
                                        </tr>
                                    <? } ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- row end -->

                        <!-- row start -->
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="data_table_paginate" style="text-align: right;padding-right: 50px;">
                                    <?=  LinkPager::widget([
                                        'pagination' => $pagination,
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
