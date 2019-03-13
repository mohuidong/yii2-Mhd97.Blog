<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AuthItem extends ActiveRecord{

	public static function tableName(){
		return '{{%auth_item}}';
	}

	/**
	 * 获取子级角色
	 * @method getRoleChildren
	 * @return {object}
	 * @example $this->getChild();
	 */
	public function getChildren(){
		return $this->hasOne(AuthItem::classname(), ['name' => 'child']);
	}

}
