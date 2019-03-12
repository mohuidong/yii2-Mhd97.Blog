<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%posts}}".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $summary 摘要
 * @property string $content 文章
 * @property string $label_img 标签图
 * @property int $cat_id 分类id
 * @property int $user_id 用户id
 * @property string $user_name 用户名
 * @property int $status 10审核中 11已审核 12审核失败
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Posts extends BaseModel
{
    /**
     * 文章分类
     */

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['summary', 'content', 'label_img', 'cat_id', 'user_id', 'user_name', 'created_at', 'updated_at'], 'required'],
            [['content'], 'string'],
            [['cat_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'summary', 'label_img', 'user_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'summary' => '摘要',
            'content' => '文章',
            'label_img' => '标签图',
            'cat_id' => '分类id',
            'user_id' => '用户id',
            'user_name' => '用户名',
            'status' => '10审核中 11已审核 12审核失败',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static $class = [
        self::CLASS_NOVICE_GUIDE => '新手指引',
        self::CLASS_LOAN_SCHOOL => '贷款学堂',
        self::CLASS_VIDEO_CLASSROOM => '视频课堂',
        self::CLASS_COMPANY_DYNAMICS => '公司动态',
        self::CLASS_CASE_SHARING => '案例分享',
        self::CLASS_SYSTEM_BULLETIN => '系统公告',
        self::CLASS_INDUSTRY_INFORMATION => '行业资讯',
    ];
}
