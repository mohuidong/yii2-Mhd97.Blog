<?php

namespace backend\controllers;

use common\models\Manager;
use Yii;
use yii\base\ActionEvent;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\AuthAssignment;

class AuthController extends Controller{

    public $enableCsrfValidation = false;



    public $defaultAction = 'users';

    private $auth;

    private $roles = ['backendManager'];

    private $permissions = [];

    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['users', 'user-role', 'user-search', 'user-assign', 'revoke', 'roles', 'role', 'role-delete'],
                        'allow' => true,
                        'roles' => ['backendManager'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'revoke' => ['post'],
                    //'role-delete' => ['post'],
                ],
            ],
        ];
    }

    public function init(){
        parent::init();
        $this->auth = \Yii::$app->authManager;
        foreach($this->auth->getChildren($this->roles[0]) as $item){
            if($item->type == 1)$this->roles[] = $item->name;
        }
        foreach(\Yii::$app->params['permissions'] as $group){
            foreach($group['items'] as $name => $description){
                if($permission = $this->auth->getPermission($name)){
                    if($permission->description != $description){
                        $permission->description = $description;
                        $this->auth->update($name, $permission);
                    }
                }else{
                    $permission = $this->auth->createPermission($name);
                    $permission->description = $description;
                    $this->auth->add($permission);
                }
                $this->permissions[$name] = $description;
            }
        }
    }

    /**
     * 角色删除
     * @param $id
     * @return array
     */
    public function actionRoleDelete($id){
        $response = ['status' => 0, 'message' => '操作失败，请重试'];

        $role = $this->auth->getRole($id);
        if($role && $role->name != $this->roles[0] && $this->auth->remove($role)){
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }

        \Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionRole($name = null){
        $role = $this->auth->getRole($name);

        if(\Yii::$app->request->isPost){
            $_name = \Yii::$app->request->post('name');
            $_description = \Yii::$app->request->post('description');
            $permissions = \Yii::$app->request->post('permissions', []);
            if(empty($_name)){
                $error = '角色英文名称不能为空';
            }else if(empty($_description)){
                $error = '角色中文备注不能为空';
            }else if(!$role && $this->auth->getRole($_name)){
                $error = '角色英文名已存在, 请设置其它英文名';
            }
            if(isset($error)){
                \Yii::$app->session->setFlash('name', $_name);
                \Yii::$app->session->setFlash('description', $_description);
                \Yii::$app->session->setFlash('permissions', $permissions);
                \Yii::$app->session->setFlash('error', $error);
                return $this->refresh();
            }else{
                if($role){
                    if($role->name != $this->roles[0])$role->name = $_name;
                }else{
                    $name = $_name;
                    $role = $this->auth->createRole($name);
                    $this->auth->add($role);
                    $this->auth->addChild($this->auth->getRole($this->roles[0]), $role);
                }
                $role->description = $_description;
                if($this->auth->update($name, $role)){
                    foreach($permissions as $pname){
                        $permission = $this->auth->getPermission($pname);
                        if(!$this->auth->hasChild($role, $permission)){
                            $this->auth->addChild($role, $permission);
                        }
                        unset($this->permissions[$pname]);
                    }
                    foreach($this->permissions as $pname => $description){
                        $permission = $this->auth->getPermission($pname);
                        if($this->auth->hasChild($role, $permission)){
                            $this->auth->removeChild($role, $permission);
                        }
                    }
                }
                return $this->redirect(['/auth/roles']);
            }
        }

        return $this->render($this->action->id, [
            'auth' => $this->auth,
            'role' => $role,
            'root' => $this->roles[0],
        ]);
    }

    public function actionRoles(){
        $roles = [];
        foreach($this->roles as $role){
            $roles[] = $this->auth->getRole($role);
        }

        return $this->render($this->action->id, [
            'roles' => $roles,
            'authAssignment' => AuthAssignment::find(),
        ]);
    }

    /**
     * 用户删除
     * @param $uid
     * @return array
     */
    public function actionRevoke($uid, $id){
        $response = ['status' => 0, 'message' => '操作失败，请重试'];

        $role = $this->auth->getRole($id);
        if($role && $this->auth->revoke($role, $uid)){
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }

        \Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionUserAssign(){
        $response = ['status' => 0, 'message' => '操作失败，请重试'];
        $uid = \Yii::$app->request->post('uid');
        $roleName = \Yii::$app->request->post('role');
        $revokeName = \Yii::$app->request->post('revoke');

        if(!empty($uid) && !empty($roleName) && in_array($roleName, $this->roles) && $role = $this->auth->getRole($roleName)){
            if(!empty($revokeName) && in_array($revokeName, $this->roles) && $revoke = $this->auth->getRole($revokeName)){
                $this->auth->revoke($revoke, $uid);
            }
            if($assign = $this->auth->assign($role, $uid)){
                $user = Manager::findOne($assign->userId);
                $response['status'] = 1;
                $response['message'] = '操作成功';
                $response['data'] = [
                    'uid' => $user->id,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'name' => $role->name,
                    'description' => $role->description,
                ];
            }
        }

        \Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionUserSearch($phone){
        $response = ['status' => 0, 'message' => '操作失败，请重试'];

        if($user = Manager::findByUsername($phone)){
            if(AuthAssignment::find()->where('user_id = ' . $user->id . ' and item_name in ("' . implode('", "', $this->roles) . '")')->count() > 0){
                $response['message'] = '此用户已设定权限';
            }else{
                $response['status'] = 1;
                $response['message'] = '操作成功';
                $response['data'] = $user;
            }
        }else{
            $response['message'] = '未匹配到用户';
        }

        \Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionUserRole($uid = null){
        $response = ['status' => 0, 'message' => '操作失败，请重试'];

        $data = ['roles' => []];
        foreach($this->roles as $role){
            $data['roles'][] = $this->auth->getRole($role);
        }
        if(!empty($uid)){
            $data['user'] = Manager::findOne($uid);
        }

        $response['status'] = 1;
        $response['message'] = '操作成功';
        $response['data'] = $data;

        \Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionUsers(){
        $query = AuthAssignment::find()->where('item_name in ("' . implode('", "', $this->roles) . '")');

        $pagination = new Pagination([
            'totalCount' => $query->count(),
        ]);

        $assigns = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render($this->action->id, [
            'assigns' => $assigns,
            'pagination' => $pagination,
        ]);
    }

}
