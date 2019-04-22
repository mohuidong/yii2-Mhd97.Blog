<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\base\BaseModel;

/**
 * This is the model class for table "{{%question}}".
 *
 * @property int $id 问题id
 * @property int $user_id 发布者id
 * @property string $question 标题
 * @property string $content 内容
 * @property int $best_id 最优秀回答id
 * @property int $best_user_id 最优秀回复id
 * @property int $created_at 发布时间
 * @property int $updated_at 更新时间
 */
class Question extends \yii\db\ActiveRecord
{
    const STATUS_WAIT = 0;

    const STATUS_PASS = 1;

    const STATUS_NO = 2;

    const STATUS_SOLVE = 3;

    const MANAGER_UID = 0;

    const MANAGER_NAME = "很水的菜鸟管理员";

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseModel::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    BaseModel::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'best_id', 'best_user_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['question'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'question' => '问题',
            'status' => '状态',
            'content' => '描述',
            'best_id' => '最佳回复',
            'best_user_id' => '最佳回复用户',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static $status = [
        self::STATUS_WAIT => '审核中',
        self::STATUS_PASS => '审核通过',
        self::STATUS_NO => '审核失败',
        self::STATUS_SOLVE => '已解决',
    ];
}
