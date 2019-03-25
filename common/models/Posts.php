<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;
use common\models\PostClass;
use yii\behaviors\TimestampBehavior;

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
     * 正在审核
     */
    const STATUS_WAIT = 10;

    /**
     * 审核通过
     */
    const STATUS_PASS = 11;

    /**
     * 审核失败
     */
    const STATUS_FAILURE= 12;

    /**
     * 文章分类 后端
     */
    const CLASS_BACKEND = 4;

    /**
     * 文章分类 前端
     */
    const CLASS_FRONTED = 5;

    /**
     * 文章分类 LINUX
     */
    const CLASS_LINUX = 6;

    /**
     * 文章分类 ORDER
     */
    const CLASS_ORDER = 9;

    /**
     * 文章分类 GIT
     */
    const CLASS_GIT = 8;

    /**
     * 文章分类 数据库
     */
    const CLASS_SQL = 7;

    /**
     * 管理员id 默认为0
     */
    const MANAGER_UID = 0;

    /**
     * 管理员username 默认为0
     */
    const MANAGER_NAME = '官方唯一指定管理员';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseModel::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseModel::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

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
            [['summary', 'content', 'label_img', 'cat_id', 'user_id', 'user_name'], 'required'],
            [['content'], 'string'],
            [['cat_id', 'user_id', 'status'], 'integer'],
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
            'likes' => '点赞',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static $class = [
        self::CLASS_BACKEND => '后端',
        self::CLASS_FRONTED => '前端',
        self::CLASS_LINUX => 'LINUX',
        self::CLASS_GIT => 'git',
        self::CLASS_ORDER => '其他',
        self::CLASS_SQL => '数据库',
    ];

    public static $status = [
        self::STATUS_WAIT =>'正在审核',
        self::STATUS_PASS =>'审核成功',
        self::STATUS_FAILURE =>'审核失败',
    ];


    public  static function getClassText($cat){
        return self::$class[$cat];
    }

    /**
     * 获取完整地址
     * @return string
     */
    public function getFullAddress($img = '')
    {
        return Yii::$app->params['domain'] . ($img ?: $this->label_img);
    }
}
