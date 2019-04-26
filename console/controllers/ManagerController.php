<?php

namespace console\controllers;

use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\AuthItemChild;
use common\models\Manager;
use Yii;
use yii\console\Controller;

class ManagerController extends Controller{

	public function actionIndex(){
        $manager = new Manager();
        $manager->username = 'admin';
        $manager->generateAuthKey();
        $manager->setPassword('123456');
        $manager->email = '';
        $manager->save();

        $manager1 = new Manager();
        $manager1->username = 'mohuidong';
        $manager1->generateAuthKey();
        $manager1->setPassword('123456');
        $manager1->email = '';
        $manager1->save();

        $manager2 = new Manager();
        $manager2->username = 'wxxwxx';
        $manager2->generateAuthKey();
        $manager2->setPassword('wxx1010');
        $manager2->email = '';
        $manager2->save();

        $authItem = new AuthItem();
        $authItem->name = 'root';
        $authItem->type = 1;
        $authItem->description = '真理';
        $authItem->save();

        $authItem1 = new AuthItem();
        $authItem1->name = 'backendManager';
        $authItem1->type = 1;
        $authItem1->description = '博客后台管理员';
        $authItem1->save();

        $authItemChild = new AuthItemChild();
        $authItemChild->parent = 'root';
        $authItemChild->child = 'backendManager';
        $authItemChild->save();

        $authAssignment = new AuthAssignment();
        $authAssignment->item_name = 'root';
        $authAssignment->user_id = $manager->id;
        $authAssignment->created_at = time();
        $authAssignment->save();

        $authAssignment1 = new AuthAssignment();
        $authAssignment1->item_name = 'backendManager';
        $authAssignment1->user_id = $manager1->id;
        $authAssignment1->created_at = time();
        $authAssignment1->save();

        $authAssignment2 = new AuthAssignment();
        $authAssignment2->item_name = 'backendManager';
        $authAssignment2->user_id = $manager2->id;
        $authAssignment2->created_at = time();
        $authAssignment2->save();
	}
}
