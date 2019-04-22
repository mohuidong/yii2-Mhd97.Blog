<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use common\models\base\BaseModel;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    /**
     * 状态 正常
     */
    const STATUS_NORMAL = 10;

    /**
     * 状态 封号
     */
    const STATUS_BAN = 11;

    /**
     * 角色等级 吃瓜群众
     */
    const ROLE_ONE = 10;

    /**
     * 角色等级 带头搞事
     */
    const ROLE_TWO = 20;

    /**
     * 角色等级 吧啦大王
     */
    const ROLE_THREE = 30;

    /**
     * 角色等级 传销头目
     */
    const ROLE_FOUR = 40;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone', 'role', 'status'], 'integer'],
            ['password_hash', 'string', 'min' => 6 ],
            ['password_hash', 'match' , 'pattern' => '/^[!-~]+$/' , 'message' => '请输入英文，数字'],
            ['phone' , 'match' , 'pattern' => '/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9]|17[0|1|2|3|5|6|7|8|9])\d{8}$/', 'message' => '请输入正确的手机号码'],
            [['auth_key', 'access_token'], 'string', 'max' => 32],
            [['password_reset_token', 'email_validate_token', 'email', 'avatar'], 'string', 'max' => 255],
            [['username','nickname'], 'string', 'max' => 20],
            ['phone' , 'unique' , 'targetClass' =>'\common\models\User' , 'message'=>'电话号码已使用过'],
            ['email' , 'unique' , 'targetClass' =>'\common\models\User' , 'message'=>'该邮箱已被使用'],
            ['nickname' , 'unique' , 'targetClass' =>'\common\models\User' , 'message'=>'该昵称已被使用'],
            ['status', 'default', 'value' => self::STATUS_NORMAL],
            ['role', 'in', 'range' => [self::ROLE_ONE, self::ROLE_TWO, self::ROLE_THREE, self::ROLE_FOUR]],
            ['status', 'in', 'range' => [self::STATUS_NORMAL, self::STATUS_BAN]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'auth_key' => 'auth_key',
            'username' => '用户名',
            'nickname' => '昵称',
            'password_hash' => '密码',
            'password_reset_token' => '密码重置令牌',
            'email' => '邮箱',
            'phone' => '手机号',
            'access_token' => '认证令牌',
            'avatar' => '头像',
            'role' => '1融资客 2经纪人 3信贷员',
            'status' => '状态 1正常 2封号',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_NORMAL]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['access_token' => $token])->andWhere(['>', 'expire_at', TIMESTAMP])->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_NORMAL]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_NORMAL,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public static function getUsername($id)
    {
        if ($id == 0) {
            return "官方唯一指定管理员";
        }
        return static::findOne($id)->nickname;
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        return $this->access_token;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * 用户等级映射
     * @var array
     */
    public static $roles = [
        self::ROLE_ONE  => '吃瓜群众',
        self::ROLE_TWO   => '带头搞事',
        self::ROLE_THREE => '吧啦大王',
        self::ROLE_FOUR => '传销头目',
    ];

    /**
     * 获取用户类型文本名称
     * @return object
     */
    public function getRoleText()
    {
        return self::$roles[$this->role];
    }

}
