<?php
namespace common\components;

class ApiSerializer extends \yii\rest\Serializer
{
    public $additionalAttributes = [];

    public function serialize($data)
    {
        return $this->additionalAttributes ? array_merge(parent::serialize($data), ['additionalAttributes' => $this->additionalAttributes]) : parent::serialize($data);
    }
}