<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class AuthAssignment extends ActiveRecord{

	public static function tableName(){
		return '{{%auth_assignment}}';
	}

	/**
	 * 获取用户信息
	 * @method getUser
	 * @return {object}
	 * @example $this->getUser();
	 */
	public function getUser(){
		return $this->hasOne(Manager::classname(), ['id' => 'user_id']);
	}

	/**
	 * 获取角色信息
	 * @method getItem
	 * @return {object}
	 * @example $this->getItem();
	 */
	public function getItem(){
		return $this->hasOne(AuthItem::classname(), ['name' => 'item_name']);
	}

}
