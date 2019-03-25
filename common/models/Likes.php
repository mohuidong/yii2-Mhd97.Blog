<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%likes}}".
 *
 * @property int $id 点赞表id
 * @property int $posts_id 文章id
 * @property int $user_id 用户id
 * @property string $ip ip地址
 * @property string $created_at 点赞时间
 * @property int $status 状态 0 取消 1点赞
 */
class Likes extends \yii\db\ActiveRecord
{
    /**
     * 点赞
     */
    const LIKES_YES = 1;

    /**
     * 没有点赞
     */
    const LIKES_NO = 0;

    /**
     * 取消点赞
     */
    const LIKES_CANCEL = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%likes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['posts_id', 'user_id', 'status'], 'integer'],
            [['status'], 'required'],
            [['ip', 'created_at'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'posts_id' => 'Posts ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }
}
