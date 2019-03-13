<?php

use yii\helpers\Html;
use common\models\SystemSetting;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$modelLabel=new \common\models\SystemSetting();
$this->title = '站点信息';
$this->params['breadcrumbs'][] = $this->title;
?>



    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body ">
                    <div class="col-xs-12">
                        <table class="table table-bordered table-hover table-striped dataTable">
                            <thead>
                            <tr role="row">
                                <th>类型</th>
                                <th>数据</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php foreach ($systemSetting as $key => $value) :?>
                                <tr>
                                    <td class="J-q-id" style="display: none"><?=$key?></td>
                                    <td><?=$type[$key]?></td>
                                    <td class="J-q-number"><?= $value?></td>
                                    <td>
                                        <a
                                                class = "<?= $key == 'finance_information'? 'J-q-checked' : 'J-q-prompt'?> btn btn-danger btn-sm"
                                                href = "javascript:;"
                                                data-text = "修改"
                                                data-key = "value"
                                                data-value = "<?=$value?>"
                                                data-url = "/system-setting/update?key=<?=$key?>"
                                                data-method = "post"
                                        >
                                        修改
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        </table>

                    </div>


                    <div class="col-sm-12">
                        <div >
                            <div>
                                <div>共<?=count($systemSetting)?>条数据</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>





<script>
    window.onload = function () {
        ;(function() {
            $('.J-q-checked').on('click',function (ev) {
                var $itme = $(ev.currentTarget).parents('tr')
                var id = $itme.find('.J-q-id').text()
                var currentNumber =  $itme.find('.J-q-number').text()
                var csrfKey = $('meta[name=csrf-param]').attr('content')
                var csrfValue = $('meta[name=csrf-token]').attr('content')

                if(id.trim() === 'finance_information' ) {
                    $.ajax({
                        url: '/system-setting/get-information',
                        type: "GET",
                        success: function (data) {
                            var informationArray = data['data']

                            if (data['status'] === 1) {
                                var currentNumberArray = currentNumber.trim().length === 0 ? [] : JSON.parse(currentNumber)

                                var currentCheckObj = currentNumberArray.reduce(function (obj, value) {
                                    obj[value] = true
                                    return obj
                                }, {})

                                function createTr(informationArray, currentCheckObj) {
                                    var domString = ``
                                    for (var i = 0; i <= informationArray.length - 1; i++) {
                                        var itmeString = `
                    <tr data-id=${informationArray[i]['id']}>
                        <td><input  type="checkbox" ${informationArray[i]['id'] in currentCheckObj ? `checked` : ``}></td>
                        <td>${informationArray[i]['id']}</td>
                        <td>${informationArray[i]['title']}</td>
                    </tr>
            `
                                        domString += itmeString
                                    }
                                    return domString
                                }

                                var popDom = `
     <div class="row" style="margin: 0">
        <div class="col-sm-12">
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr role="row">
                        <th class="col-sm-2">选项框</th>
                        <th class="col-sm-2">id</th>
                        <th class="col-sm-8">标题</th>
                    </tr>
                </thead>

                <tbody>
                    ${createTr(informationArray, currentCheckObj)}
                </tbody>
            </table>

            <button id="J-q-information-save" type="submit" class="btn btn-success" style="margin: 12px 0">保存</button>
        </div>
    </div>`;
                                layer.open({
                                    type: 1,
                                    area: ['720px', '600px'],
                                    title: '修改数据',
                                    shade: 0.6,
                                    maxmin: false,
                                    anim: 1,
                                    content: popDom,
                                    success: function () {
                                        $('#J-q-information-save').on('click', function (ev) {
                                            var $table = $(ev.currentTarget).siblings('table')
                                            var $checked = $table.find('input:checked')
                                            if ($checked.length > 4) {
                                                layer.msg('最多只能提交4个选项！')
                                                return false
                                            } else {
                                                var ids = []
                                                for (var i = 0; i <= $checked.length - 1; i++) {
                                                    ids.push($checked.eq(i).parents('tr').attr('data-id'))
                                                }


                                                var data = {
                                                    value: JSON.stringify(ids),
                                                }
                                                data[csrfKey] = csrfValue

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '/system-setting/update?key=finance_information',
                                                    data: data,
                                                    success: function (responseData) {
                                                        console.log(responseData);
                                                        if (responseData['status'] === 1) {
                                                            layer.msg('更改成功！')
                                                            setTimeout(function () {
                                                                window.location.reload()
                                                            }, 1000)
                                                        } else {
                                                            layer.msg(responseData['message'])
                                                        }
                                                    }
                                                })
                                            }
                                        })

                                    }
                                })

                            } else {
                                layer.msg(data['message'])
                            }

                        },
                        error: function () {
                            layer.msg('出错了，请联系管理员！')
                        }
                    })
                }

            })
        })(window)
    }
</script>
