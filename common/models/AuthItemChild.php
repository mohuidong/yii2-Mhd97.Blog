<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AuthItemChild extends ActiveRecord{

	public static function tableName(){
		return '{{%auth_item_child}}';
	}

	/**
	 * 获取父级角色信息
	 * @method getParent
	 * @return {object}
	 * @example $this->getParent();
	 */
	public function getParent(){
		return $this->hasOne(AuthItem::classname(), ['name' => 'parent']);
	}

	/**
	 * 获取子级角色信息
	 * @method getChild
	 * @return {object}
	 * @example $this->getChild();
	 */
	public function getChild(){
		return $this->hasOne(AuthItem::classname(), ['name' => 'child']);
	}

}
