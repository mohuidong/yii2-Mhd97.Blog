<?php

namespace api\modules\v1\controllers;

use common\models\SystemSetting;
use yii\web\BadRequestHttpException;
class SystemSettingController extends BaseController
{
    public function actionIndex()
    {
        $systemSetting = SystemSetting::findToArray();
        $keys = [
            SystemSetting::KEY_WECHAT,
            SystemSetting::KEY_WEBSITE_TITLE,
            SystemSetting::KEY_QQ,
            SystemSetting::KEY_GITHUB,
            SystemSetting::KEY_LANGUAGE,
            SystemSetting::KEY_CUSTOMER_SERVICE_EMAIL,
            SystemSetting::KEY_CUSTOMER_SERVICE_PHONE,
            SystemSetting::HOME_SIGNATURE_TITLE,
            SystemSetting::HOME_SIGNATURE_CONTENT,
            SystemSetting::FOOTER_RIGHT,
            SystemSetting::FOOTER_LEFT,
//            SystemSetting::BG_HOME,
//            SystemSetting::BG_ABOUT,
//            SystemSetting::BG_CLASS,
//            SystemSetting::BG_ISSUE,
//            SystemSetting::BG_ME,
//            SystemSetting::BG_REWARD,
            SystemSetting::KEY_COUNT_REGISTRATIONS,
        ];
        $data = [];
        foreach ($systemSetting as $field => $val) {
            if (!in_array($field, $keys)) {
                continue;
            }
            $data[$field] = $val;
        }
        return $data?:[];
    }
}
