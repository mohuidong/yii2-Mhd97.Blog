<?php
namespace backend\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $sysInfo = [
            ['name'=> '操作系统', 'value'=>php_uname('s')],  //'value'=>php_uname('s').' '.php_uname('r').' '.php_uname('v')],
            ['name'=>'PHP版本', 'value'=>phpversion()],
            ['name'=>'Yii版本', 'value'=>Yii::getVersion()],
            ['name'=>'数据库', 'value'=>$this->getDbVersion()],
            ['name'=>'AdminLTE', 'value'=>'V2.3.6'],
        ];
        $today = strtotime(date('Y-m-d'));
        $user = [];
        $users = User::find();
        $user['1'] = $users;
        $user['2'] = $users;
        $user['3'] = $users;
        $user['4'] = $users;
        $user['5'] = $users;
        $user['1']=$this->day(1, $user['1'], 'created_at')->count();
        $user['2']=$this->day(2, $user['2'], 'created_at')->count();
        $user['3']=$this->day(3, $user['3'], 'created_at')->count();
        $user['4']=$this->day(4, $user['4'], 'created_at')->count();
        $user['5']=$this->day(5, $user['5'], 'created_at')->count();


        return $this->render('index', [
            'sysInfo'=>$sysInfo,
            'user' => $user,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 获取数据库版本
     * @return string
     * @throws \yii\db\Exception
     */
    private function getDbVersion()
    {
        $driverName = Yii::$app->db->driverName;
        if(strpos($driverName, 'mysql') !== false){
            $v = Yii::$app->db->createCommand('SELECT VERSION() AS v')->queryOne();
            $driverName = $driverName .'_' . $v['v'];
        }
        return $driverName;
    }

    public function day($days=1, $query, $field)
    {
        $oneday=24*60*60;
        $today1 = strtotime(date('Y-m-d'));//今天
        $today2 = $today1 - $oneday;//昨天
        $today3 = $today2 - $oneday;//前天
        $today4 = $today3 - $oneday;//大前天
        $today5 = $today4 - $oneday;//大大前天
        switch ($days){
            case 1:
                $query= $query->andWhere("$field > $today1");break;
            case 2:
                $query= $query->andWhere(["between", "$field", $today2, $today1]);break;
            case 3:
                $query= $query->andWhere(["between", "$field", $today3, $today2]);break;
            case 4:
                $query= $query->andWhere(["between", "$field", $today4, $today3]);break;
            case 5:
                $query= $query->andWhere(["between", "$field", $today5, $today4]);break;

        }
        return $query;
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
