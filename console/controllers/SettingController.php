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
            'key_qq' => '914685196',
            'key_language' => 'PHP,JAVASCRIPT',
            'key_github' => 'https://github.com/mohuidong',
            'href' => 0,
            'home_signature_title' => 'Mhd97',
            'home_signature_content' => '非淡泊无以明志 非宁静无以致远',
            'footer_right' => 'https://github.com/mohuidong/Mhd97.Blog.vue',
            'footer_left' => 'https://www.bilibili.com/',
            'bg_home' => '',
            'bg_class' => '',
            'bg_issue' => '',
            'bg_me' => '',
            'bg_about' => '',
            'bg_reward' => '',
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
