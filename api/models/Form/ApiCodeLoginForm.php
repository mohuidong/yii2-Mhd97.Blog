<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8 0008
 * Time: 下午 5:12
 */
namespace api\models\Form;

use common\models\User;
use common\service\Captcha;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ApiCodeLoginForm extends Model
{
    public $phone;
    public $code;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // phone and password are both required
            [['phone', 'code'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['code', 'validateCode'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCode($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $captcha = new Captcha($this->phone, 'api.login.phone');
            if (!$user || !$captcha->verify($this->code, true)) {
                $this->addError($attribute, '短信验证码错误');
            }
        }
    }

    /**
     * Logs in a user using the provided phone and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $accessToken = $this->_user->generateAccessToken();
            $expireAt = TIMESTAMP + 60 * 30; // 设定token过期时间
            $this->_user->expire_at = $expireAt;
            if ($this->_user->save(false) == false) {
                Yii::error($this->_user->getFirstErrors(), 'login');
                return false;
            }

            return  [
                'uid' => $this->_user->id,
                'type' => $this->_user->type,
                'access_token' => $accessToken,
                'expire_at' => $expireAt
            ];
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[phone]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByPhone($this->phone);
        }

        return $this->_user;
    }
}
