<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

$this->title = '角色管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['navtabs'] = [
	['label' => '用户设置', 'url' => ['/auth/users']],
	['label' => '角色管理', 'url' => ['/auth/roles'], 'active' => 1],
];
$this->params['route'] = '/dashboard/index';
?>



    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <?= Html::a('增加角色', ['/auth/role'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li ><a href="/auth">用户设置</a></li>
                        <li class="active"><?= Html::a('角色管理', ['javascript:;'], ['data-auth-user' => 'add']) ?></li>
                    </ul>
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="data_table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="data_table_info">
                                    <thead>
                                    <tr role="row">
                                        <th>角色名称</th>
                                        <th>角色描述</th>
                                        <th>用户数</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($roles as $index => $role){ ?>
                                        <tr>
                                            <td><?= $role->name ?></td>
                                            <td><?= $role->description ?></td>
                                            <td><?= $authAssignment->where("item_name = '$role->name'")->count() ?></td>
                                            <td>
                                                <a class="btn btn-primary btn-sm" href="<?=Url::to(['/auth/role', 'name' => $role->name])?>"> <i class="glyphicon glyphicon-edit icon-white"></i>配置</a>

                                                <?php if($index){ ?>
                                                    <a class="btn btn-danger btn-sm J-q-confirm" data-url="<?=Url::to(['/auth/role-delete', 'id' => $role->name])?>" data-text="确定要删除此角色吗?"> <i class="glyphicon glyphicon-trash icon-white"></i>删除</a>
                                                <? } ?>
                                            </td>
                                        </tr>
                                    <? } ?>
                                    </tbody>

                                </table>
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

