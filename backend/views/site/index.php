<?php
use yii\helpers\Url;
use common\models\UserRole;

$user_id = Yii::$app->user->identity->id;
//$role = UserRole::find()->where(['user_id'=>$user_id])->one();
$role['role_id'] = 1;
?>
<script src="/js/echarts.js"></script>
<style>
    ul,li{
        list-style: none;
    }
    .list-div{
        border: 1px #DCDCDC solid;
        background: #F7F7F7;
        height: 90px;
        padding: 0px;
        margin: 0px;
    }
    .import{
        border: 1px #DCDCDC solid;
        background: #F7F7F7;
        height: 90px;
        padding: 0px;
        margin: 0px;
    }
    .import_1{
        list-style: none;
        width: 20%;
        height: 92px;
        float: left;
        padding: 20px 0px
    }
    .import_2{
        list-style: none;
        width: 20%;
        height: 92px;
        float: left;
        padding: 20px 0px;


    }
    .import_2 .module{
        border-left: 1px solid #ccc;
    }
    .module{
        width: 100%;
        height: 100%;
    }
    .module i{
        background: url('<?=Url::base()?>/mobile/web/images/import.png') no-repeat left top;
        height: 50px;
        width: 50px;
        display: block;
        float: left;
        margin-left: 30px;
        margin-right: 10px;
    }
    .import_1 i{
        background-position: -50px 0px;
    }
    .import_2 i{
        background-position: -95px 0px;
    }
    .detail{
        height: 50px;
        width: 55%;
        float: left;
    }
    .detail strong{
        display: block;
        font-size: 16px;
        color: rgb(83, 85, 84);
        font-family: 微软雅黑;
    }
    .detail span{
        display: block;
        font-size: 15px;
        color: rgb(83, 85, 84);
        font-family: 微软雅黑;
    }
</style>
<!-- Main content -->

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-md-12">
        <?php if($role['role_id'] != 3){ ?>
            <div class="list-div">
                <ul class="import">
                    <li class="import_2">
                        <div class="module">
                            <i></i>
                            <div class="detail">
                                <strong><?=$user['1']?></strong>
                                <span>今日注册会员</span>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
            <br>
            <div id="member" style="float: left; width: 600px;height:400px;"></div>
            <div id="main" style="float: left;width: 600px;height:400px;"></div>
            <div id="commission" style="float: left;width: 600px;height:400px;"></div>
            <script type="text/javascript">
                // 基于准备好的dom，初始化echarts实例
                var myChart = echarts.init(document.getElementById('member'));

                // 指定图表的配置项和数据
                var option = {
                    title: {
                        text: '会员注册量走势'
                    },
                    tooltip: {},
                    legend: {
                        data:['注册量']
                    },
                    xAxis: {
                        data: ["<?=date("Y-m-d",strtotime("-4 day"))?>","<?=date("Y-m-d",strtotime("-3 day"))?>","<?=date("Y-m-d",strtotime("-2 day"))?>","<?=date("Y-m-d",strtotime("-1 day"))?>","<?=date('Y-m-d')?>"]
                    },
                    yAxis: {},
                    series: [{
                        name: '注册量',
                        type: 'line',
                        data: [<?=$user['5']?>, <?=$user['4']?>, <?=$user['3']?>, <?=$user['2']?>, <?=$user['1']?>]
                    }]
                };
                // 使用刚指定的配置项和数据显示图表。
                myChart.setOption(option);
            </script>
            <br>
            <div style="clear: both"></div>
        <?php } ?>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">系统信息</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 10px">#</th>
                        <th style="width: 200px">名称</th>
                        <th>信息</th>
                        <th style="width: 200px">说明</th>
                    </tr>
                    <?php
                    $count = 1;
                    foreach($sysInfo as $info){
                        echo '<tr>';
                        echo '  <td>'. $count .'</td>';
                        echo '  <td>'.$info['name'].'</td>';
                        echo '  <td>'.$info['value'].'</td>';
                        echo '  <td></td>';
                        echo '</tr>';
                        $count++;
                    }
                    ?>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">

            </div>
        </div>
        <!-- /.box -->
    </div>


</div>

<!-- /.row -->
<!-- Main row -->
<div class="row">

</div>
<!-- /.row (main row) -->


<!-- /.content -->
