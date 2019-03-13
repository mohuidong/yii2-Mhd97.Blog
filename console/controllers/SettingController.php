<?php
namespace console\controllers;

use common\models\SystemSetting;
use Yii;
use yii\console\Controller;

class SettingController extends Controller{

    /**
     * 迁移首页配置数据
     * @method actionIndex
     * @return {none}
     * @example ./yii systemSetting
     */
	public function actionIndex(){
	    $startSetting = [
	        'wechat' => 'Mo_Hui_Dong_1997',
            'website_title' => 'Mhd97-Blog',
            'customer_service_email' => '914685196@qq.com',
            'customer_service_phone' => '15295834923',
            'count_registrations' => 0,
            'customer_service_qq' => '914685196',
            'popular_search' => 0,
            'href' => 0,

        ];
	    foreach ($startSetting as $key => $value) {
            $newSetting = new SystemSetting();
            $newSetting->key = $key;
            $newSetting->value = $value;
            if ($newSetting->save(false)) {
                echo '生成默认设置（'. $newSetting->key .'） 成功';
                echo "\n";
            } else {
                echo '生成默认设置（'. $newSetting->key .'） 失败！！！';
                echo "\n";
            }
        }
	}

}
