<?php

namespace common\models;

use Yii;
use common\models\base\BaseModel;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%content}}".
 *
 * @property integer $id
 * @property string $content_name
 * @property integer $type
 * @property integer $create_time
 */
class PostClass extends BaseModel
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseModel::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name'], 'string', 'max' => 20],
            [['order'], 'integer'],
            ['class_name' , 'unique' , 'targetClass' =>'\common\models\Post-class' , 'message'=>'分类名称重复了'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_name' => '分类名称',
            'created_at' => '创建时间',
            'order' => '排序',
        ];
    }

    public static function formatData($model)
    {
        $data = [];
        foreach($model as $list){
            $data[$list->id] = $list->class_name;
        }
        return $data;
    }
}
