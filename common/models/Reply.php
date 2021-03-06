<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%reply}}".
 *
 * @property int $reply_id 回复id
 * @property int $posts_id 文章id
 * @property int $user_name 用户名
 * @property string $content 回复内容
 * @property int $created_at 回复时间
 */
class Reply extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reply}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'posts_id', 'user_id'], 'required'],
            [['reply_id', 'posts_id', 'user_id', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
            [['reply_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reply_id' => 'Reply ID',
            'posts_id' => 'Posts ID',
            'user_id' => 'User id',
            'content' => '评论',
            'created_at' => '创建时间',
        ];
    }
}
