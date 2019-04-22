<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8 0008
 * Time: 下午 5:12
 */
namespace api\models\Form;

use Yii;
use common\models\User;

/**
 * Login form
 */
class FindUserForm extends User
{
    public $code;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            [['username'], 'exist', 'message' => '该手机号未注册'],
        ];
    }

    /**
     * Logs in a user using the provided phone and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->getUser()) {
            $accessToken = $this->_user->generateAccessToken();
            $expireAt = TIMESTAMP + 60 * 30; // 设定token过期时间
            $this->_user->expire_at = $expireAt;
            $this->_user->save();

            return  [
                'uid' => $this->_user->id,
                'role' => $this->_user->role,
                'access_token' => $accessToken,
                'expire_at' => $expireAt
            ];
        } else {
            return false;
        }

        return false;
    }

    /**
     * Finds user by [[phone]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
