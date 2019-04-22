<?php

namespace api\modules\v1\controllers;

use api\models\Form\FindUserForm;
use api\models\Form\UserForm;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use OSS\OssClient;

class UserController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        if (!in_array(Yii::$app->controller->action->id, ['create', 'reset'])) {
            $behaviors['authenticator'] = [
                'class' => QueryParamAuth::className(),
            ];
        }
        return $behaviors;
    }

    /**
     *
     * @param string $filter
     * @return array
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        if (!empty($user)){
            $arr = [
                'avatar' => Yii::$app->params['domain'] . $user['avatar'],
                'id' => $user['id'],
                'nick' => $user['nickname'],
                'signature' => $user['signature'],
                'phone' => $user['phone'],
                'email' => $user['email'],
                'role' => $user['role'],
                'userName' => $user['username'],
                'created_at' => $user['created_at'],
            ];
            return $arr;
        } else {
            throw new BadRequestHttpException('你还未登录');
        }

    }

    /**
     * 注册
     * @return array
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $phone = $request->getBodyParam('username');
        $password = $request->getBodyParam('password');
        $rePassword = $request->getBodyParam('rePassword');
        $model = new UserForm();
        $model->username = $phone;
        $model->password_hash = Yii::$app->security->generatePasswordHash($password);
        $model->generateAuthKey();

        if ($model->validate()) {
            $model->phone = $phone;
            if ($model->save()== false) {
                throw new BadRequestHttpException();
            }
            return $this->dataHandle($model, [
                'auth_key',
                'password_hash',
                'payment_password_hash',
                'password_reset_token',
                'access_token',
                'access_token',
                'expire_at',
                'updated_at',
            ]);
        } else {
            throw new BadRequestHttpException(array_values($model->getFirstErrors())[0]);
        }
    }

    public function actionUpdate()
    {
        $nickcame = Yii::$app->request->getBodyParam('nickname');
        $email = Yii::$app->request->getBodyParam('email');
        $phone = Yii::$app->request->getBodyParam('phone');

        $user = Yii::$app->user->identity;
        $user->nickname = $nickcame;
        $user->email = $email;
        $user->phone = $phone;

        if ($user->save() == false) {
            throw new BadRequestHttpException('修改失败');
        }
    }

    /**
     * 重置密码
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionReset(){
        $request = \Yii::$app->request;
        $phone = $request->getBodyParam('phone');
        $code = $request->getBodyParam('code');
        $password = $request->getBodyParam('password');
        $model = new FindUserForm();

        $model->phone = $phone;
        $model->code = $code;
        $model->password_hash = $password;

        $password_hash = Yii::$app->security->generatePasswordHash($password);
        if ($model->validate()){
            $query = Yii::$app->db->createCommand()
                ->update('{{%user}}', ['password_hash' => $password_hash], ['phone' => $phone])
                ->execute();
            if (is_array($query)) {
                if (count($query) > 0){
                    $response['message'] = '修改成功';
                    $response['name'] = 'SUCCESSFUL_OPERATION';
                    return $response;
                }else{
                    throw new BadRequestHttpException('修改失败');
                }
            }
        }else {
            throw new BadRequestHttpException(array_values($model->getFirstErrors())[0]);
        }
    }

    public function actionSetAvatar()
    {
        $img = Yii::$app->request->getBodyParam('img');
        $user = Yii::$app->user->identity;
        $avatar = $this->saveImage($img, $user['id']);
        if ($avatar != false) {
            $user->avatar = $avatar;
            if ($user->save() == false) {
                throw new BadRequestHttpException('保存图片失败');
            }
        } else {
            throw new BadRequestHttpException('上传失败');
        }

    }

    public function saveImage($base64){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)){
            //获取图片格式后缀
            $type = $result[2];
            $name = uniqid();
            $runtimePath = \Yii::getAlias("@runtime");
            $savePath = $runtimePath . DIRECTORY_SEPARATOR . 'images'. DIRECTORY_SEPARATOR . 'offlines' . DIRECTORY_SEPARATOR . date('Y/m/');

            if (!file_exists($savePath)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($savePath, 0777, true);
            }

            //转成文件流之后的本地文件地址
            $imageFile = $savePath . DIRECTORY_SEPARATOR . $name . '.' . $type;
            //阿里云存储的对象名称
            $object = 'users' . DIRECTORY_SEPARATOR . date('Y/m/') . $name;
            $base64Decode = base64_decode(str_replace($result[1], '', $base64));

            //判断图片大小
            $base64 = str_replace('data:image/'. $type .';base64,', '', $base64);
            $base64 = str_replace('=', '', $base64);
            $img_len = strlen($base64);
            $file_size = $img_len - ($img_len/8)*2;
            $file_size = number_format(($file_size/1024),2);
            if ((float)$file_size > 2048) {
                throw new BadRequestHttpException('图片大于2MB', 2005);
            }

            if (file_put_contents($imageFile, $base64Decode)){
                $aliyunOss = \Yii::$app->params['aliyunOss'];
                try{
                    $ossClient = new OssClient($aliyunOss['accessKeyId'], $aliyunOss['accessKeySecret'], $aliyunOss['endPoint']);
                    $ossClient->uploadFile($aliyunOss['bucket'], $object, $imageFile);
                    unlink($imageFile);
                    return $object;
                } catch(OssException $e) {
                    printf(__FUNCTION__ . ": FAILED\n");
                    printf($e->getMessage() . "\n");
                    return false;
                }
            } else {
                throw new BadRequestHttpException('保存图片失败', 2005);
            }
        } else {
            throw new BadRequestHttpException('base64格式错误', 2005);
        }

    }


    function isBase64($str)
    {
        $pattern = "/^(data:\s*image\/(\w+);base64,)/";
        if (preg_match($pattern, $str)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 设置昵称
     * @param $object
     * @param array $exclude
     * @return array
     */
    public function actionSetNick()
    {
            $nickName = Yii::$app->request->getBodyParamt('nickName');
            $model = Yii::$app->user->identity;
            $model->nickname = $nickName;
            if ($model->save()) {
                $response['message'] = '修改成功';
                $response['name'] = 'SUCCESS_OPERATION';
                return $response;
            } else {
                throw new BadRequestHttpException('修改失败！');
            }
    }

    /**
     * 设置性别
     * @return mixed
     */
    public function actionSetSex()
    {
            $sex = Yii::$app->request->getBodyParam('sex');
            $model = Yii::$app->user->identity;
            $model->sex = $sex;
            if ($model->save()) {
                $response['message'] = '修改成功';
                $response['name'] = 'SUCCESS_OPERATION';
                return $response;
            } else {
                throw new BadRequestHttpException('修改失败！');
            }
    }


    /**
     * 设置支付密码
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionSetPayment()
    {
            $request = \Yii::$app->request;
            $payment = $request->getBodyParam('paymentPassword');
            $paymentSet = $request->getBodyParam('paymentPasswordSet');
            $code = $request->getBodyParam('code');
            $user = Yii::$app->user->identity;
            if (!is_numeric($payment) || strlen($payment) != 6) {
                throw new BadRequestHttpException('请输入6位数字');
            }
            if ($paymentSet === $payment) {
                $user->payment_password_hash = Yii::$app->security->generatePasswordHash($paymentSet);
                if ($user->validate()) {
                    $captcha = new Captcha($user->phone,'api.Payment.phone');
                    if ($captcha->verify($code)) {
                        if ($user->save()) {
                            $response['message'] = '修改成功';
                            $response['name'] = 'SUCCESSFUL_OPERATION';
                            return $response;
                        } else {
                            throw new BadRequestHttpException('修改失败！');
                        }
                    } else {
                        throw new BadRequestHttpException('验证码错误');
                    }
                } else {
                    throw new BadRequestHttpException(array_values($user->getFirstErrors())[0]);
                }
            } else {
                throw new BadRequestHttpException('修改失败,两次密码不一致！');
            }
    }

    /**
     * 1.修改支付密码时校验原密码 2.重设支付密码
     * @return mixed
     */
    public function actionCheck()
    {
            $payment = Yii::$app->request->getBodyParam('payment');
            $user = Yii::$app->user->identity;
            if (!is_numeric($payment) || strlen($payment) != 6) {
                throw new BadRequestHttpException('请输入6位数字');
            }
            if (empty($user['payment_password_hash'])){
                throw new BadRequestHttpException('校验失败,你还没有设置支付密码');
            }
            if (Yii::$app->getSecurity()->validatePassword($payment, $user['payment_password_hash'])) {
                $response['message'] = '校验成功';
                $response['name'] = 'SUCCESS_OPERATION';
                $session = new Stepper('ResetPayments' . $user->getId());
                $session->gotoStepTwo();
                return $response;
            } else {
                throw new BadRequestHttpException('校验失败,密码错误');
            }
    }

    /**
     * 2.重设支付密码
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionResetPayments()
    {
            $user = Yii::$app->user->identity;
            $payment = Yii::$app->request->getBodyParam('payment');
            $session = new Stepper('ResetPayments' . $user->getId());
            if ($session->getCurrentStep() == 2) {
                $newPayment = Yii::$app->request->getBodyParam('newPayment');
                $resetPayment = Yii::$app->request->getBodyParam('resetPayment');
                if ($newPayment === $resetPayment) {
                    if (!is_numeric($newPayment) || strlen($newPayment) != 6) {
                        throw new BadRequestHttpException('请输入6位数字');
                    }
                    $user->payment_password_hash = Yii::$app->security->generatePasswordHash($resetPayment);
                    if ($user->save()) {
                        $response['message'] = '修改成功';
                        $response['name'] = 'SUCCESS_OPERATION';
                        $session->destroyStep();
                        return $response;
                    } else {
                        $error = array_values($user->getFirstErrors())[0];
                        throw new BadRequestHttpException('修改失败:'.$error, 2001);
                    }
                } else {
                    throw new BadRequestHttpException('两次密码不一致');
                }
            } else {
                throw new BadRequestHttpException('旧密码呢？小老弟？');
            }
    }

    public function actionResetPasswords()
    {
        $code = Yii::$app->request->getBodyParam('code');
        $password = Yii::$app->request->getBodyParam('password');
        $resetPassword = Yii::$app->request->getBodyParam('resetPassword');
        $model = Yii::$app->user->identity;
        if ($model->validate()) {
            $captcha=new Captcha($model->phone, 'api.resetPassword.phone');
            if ($captcha->verify($code)){
                if ($password === $resetPassword) {
                    $model->password_hash = Yii::$app->security->generatePasswordHash($resetPassword);
                    if ($model->save()){
                        $response['message'] = '修改成功';
                        $response['name'] = 'SUCCESSFUL_OPERATION';
                        return $response;
                    } else {
                        throw new BadRequestHttpException('修改失败');
                    }
                } else {
                    throw new BadRequestHttpException('两次密码不一致', 2001);
                }
            } else {
                throw new BadRequestHttpException('验证码错误', 2001);
            }
        }
    }

    protected function dataHandle($object, $exclude = [])
    {
        $data = [];
        foreach ($object as $field => $val) {
            // 去除不返回的数据
            if (in_array($field, $exclude)) {
                continue;
            }

            switch ($field) {
                case 'face':
                    $data[$field] = Yii::$app->params['domain'] . $val;
                    break;
                default:
                    $data[$field] = $val;
            }
        }
        return $data;
    }

    public function actionQrcodes()
    {
        $qr = Yii::$app->get('qr');
        $user = Yii::$app->user->identity;

        if ($user->type != User::TYPE_AGENT) {
            throw new BadRequestHttpException('你不是经纪人！', 2006);
        }
        $id = $user->id;
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty($user->qrcode)) {
            $user->qrcode = Yii::$app->params['domain.m'] . 'home/register?id=' . $id;
            if ($user->save()){
                $qrcode =  $qr
                    ->setSize(600)
                    ->setText($user->qrcode)
                    ->writeString();
                $qrcode = base64_encode($qrcode);
                $data = [
                    'AgentName' => $user->name ?: '未实名',
                    'Phone' => $user->phone,
                    'Qrcode' => $qrcode
                ];
                return $data;
            }
        } else {
            $qrcode =  $qr
                ->setSize(600)
                ->setText($user->qrcode)
                ->writeString();
            $qrcode = base64_encode($qrcode);
            $data = [
                'AgentName' => $user->name ?: '未实名',
                'Phone' => $user->phone,
                'Qrcode' => $qrcode
            ];
            return $data;
        }

    }


    public function actionRecommend($uid = 0, $perPage = 20)
    {
        $user = Yii::$app->user->identity;

        if ($uid) {
            $subUser = User::findOne($uid);

            if ($subUser == false || $subUser->pid != $user->id) {
                throw new BadRequestHttpException('无效的参数：uid');
            }
        }

        $query = User::find()
            ->select([
                'u.id',
                'u.type',
                'u.name',
                'u.phone',
                'uas.subordinate_user_commission',
                'uas.subordinate_user_group_commission',
                'u.agent_at',
                'u.created_at',
            ])
            ->from('{{%user}} AS u')
            ->where([
                'u.pid' => $uid ?: $user->id,
            ])
            ->join('LEFT JOIN', '{{%user_agent_stats}} AS uas', 'u.id = uas.subordinate_user_id AND uas.user_id = ' . ($uid ? 'u.grand_id' : 'u.pid'))
            ->orderBy('u.id')
            ->asArray();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $perPage,
                'validatePage' => false,
            ],
        ]);

        $models = $provider->getModels();

        foreach ($models as $index => $model) {
            $models[$index] = $model;
            $models[$index]['subordinate_user_commission'] = $model['subordinate_user_commission'] ?: '0.00';
            $models[$index]['subordinate_user_group_commission'] = $model['subordinate_user_group_commission'] ?: '0.00';
            $models[$index]['level'] = $uid ? 2 : 1;
            if (empty($model['name'])) {
                $models[$index]['name'] = '未实名';
            } else {
                if ($models[$index]['level'] == 2) {
                    $models[$index]['name'] = substr($model['name'], '0' , '3') . 'XX';
                }
            }
        }

        $provider->setModels($models);
        $name = isset($subUser) ? $subUser->name : $user->name;
        $this->serializer['additionalAttributes'] = ['name' => !empty($name) ? $name : '未实名' ];

        return $provider;
    }

    /**
     * *$url 接口url string
     * $type 请求类型 get/post string
     * $headers http请求头部添加appcode
     * @param $url
     * @param string $headers
     * @param string $type
     * @return mixed
     */
    public function httpCurl($url, $headers='',$type = 'GET')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (1 == strpos("$" . self::API_HOST, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        return curl_exec($curl);
    }

}
