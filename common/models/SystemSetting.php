<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "admin_system_setting".
 *
 * @property string $key 键
 * @property string $value 值
 */
class SystemSetting extends \yii\db\ActiveRecord
{


    /**
     * 开启
     */
    const KEY_START = 'start';

    /**
     * 微信号
     */
    const KEY_WECHAT = 'wechat';

    /**
     * 网站标题
     */
    const KEY_WEBSITE_TITLE = 'website_title';

    /**
     * 客服邮箱
     */
    const KEY_CUSTOMER_SERVICE_EMAIL = 'customer_service_email';

    /**
     * 客服热线
     */
    const KEY_CUSTOMER_SERVICE_PHONE = 'customer_service_phone';

    /**
     * 手机二维码
     */
    const KEY_MOBILE_CODE = 'mobile_code';

    /**
     * 运营时间
     */
    const KEY_OPERATE_TIME = 'operate_time';

    /**
     * 累计注册量
     */
    const KEY_COUNT_REGISTRATIONS = 'count_registrations';

    /**
     * 热门搜素
     */
    const KEY_POPULAR_SEARCH = 'popular_search';

    /**
     * 对应链接
     */
    const KEY_HREF = 'href';

    /**
     * 对应链接
     */
    const HOME_SIGNATURE_TITLE = 'home_signature_title';

    /**
     * 对应链接
     */
    const HOME_SIGNATURE_CONTENT = 'home_signature_content';

    /**
     * 对应链接
     */
    const FOOTER_RIGHT = 'footer_right';

    /**
     * 对应链接
     */
    const FOOTER_LEFT = 'footer_left';

    /**
     * 主页背景
     */
    const BG_HOME = 'bg_home';

    /**
     * 主页分类
     */
    const BG_CLASS = 'bg_class';

    /**
     * 主页问答
     */
    const BG_ISSUE = 'bg_issue';

    /**
     * 主页打赏
     */
    const BG_REWARD = 'bg_reward';

    /**
     * 主页我的
     */
    const BG_ME = 'bg_me';

    /**
     * 主页背景
     */
    const BG_ABOUT = 'bg_about';


    /**
     * 缓存key
     */
    const KEY_CACHE = 'setting';

    private static $setting = null;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%system_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'string', 'max' => 128],
            [['value'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key' => '键',
            'value' => '值',
        ];
    }

    static function getType()
    {
        return [
            self::KEY_WECHAT => '微信',
            self::KEY_WEBSITE_TITLE => '网站标题',
            self::KEY_CUSTOMER_SERVICE_EMAIL => '客服邮箱',
            self::KEY_CUSTOMER_SERVICE_PHONE => '客服热线',
            self::KEY_MOBILE_CODE => '手机二维码',
            self::KEY_OPERATE_TIME => '运营时间 如100,10,5,10',
            self::KEY_COUNT_REGISTRATIONS => '累计注册量',
            self::KEY_POPULAR_SEARCH => '热门搜索 用英文,隔开',
            self::KEY_HREF => '对应链接 用英文,隔开',
            self::HOME_SIGNATURE_TITLE => '主页标题',
            self::HOME_SIGNATURE_CONTENT => '主页内容',
            self::FOOTER_LEFT => '底部左链接',
            self::FOOTER_RIGHT => '底部左链接',
            self::BG_HOME => '主页背景',
            self::BG_CLASS => '分类背景',
            self::BG_ISSUE => '问答背景',
            self::BG_ME => '我的背景',
            self::BG_REWARD => '打赏背景',
            self::BG_ABOUT => '关于背景',
        ];
    }

    /**
     * 清除缓存
     */
    public function clearCache()
    {
        return Yii::$app->cache->delete(self::KEY_CACHE);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->clearCache();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->clearCache();
    }

    public static function get($key)
    {
        $setting = self::findToArray();

        return isset($setting[$key]) ? $setting[$key] : null;
    }

    public static function findToArray()
    {
        if (self::$setting === null) {
            $cache = Yii::$app->cache;
            $config = $cache->get(self::KEY_CACHE);
            if ($config === false) {
                $config = self::find()->all();
                $cache->set(self::KEY_CACHE, $config, 86400);
            }
            foreach ($config as $set) {
                self::$setting[$set->key] = $set->value;
            }
        }

        return self::$setting;
    }

    public static function saveSetting($val, $key)
    {
        $model = self::findOne(['key' => $key]);

        if (false == $model) {
            $model = new self();
            $model->key = $key;
        }

        $model->value = $val;

        return $model->save();
    }

    public static function getArrs($key)
    {
        $setting = self::findToArray();
        return isset($setting[$key]) ? $setting[$key] : '';
    }
}
