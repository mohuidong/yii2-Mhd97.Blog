<?php
return [
    'adminEmail' => 'admin@example.com',

    'menu' => [
        'label' => 'Mhd97.top-博客管理后台', 'url' => '/dashboard/index', 'sidebar' => [
            ['label' => '会员管理', 'url' => 'javascript:;', 'icon' => 'fa-dashboard', 'sub' => [
                ['label' => '会员列表', 'url' => '/user/index'],
            ]],
//            ['label' => '产品管理', 'url' => 'javascript:;', 'icon' => 'fa-barcode', 'sub' => [
//                ['label' => '产品列表', 'url' => '/goods/index'],
//                ['label' => '筛选功能', 'url' => '/goods-class/index'],
//                ['label' => '分类管理', 'url' => '/categories/index'],
//            ]],
//            ['label' => '订单管理', 'url' => 'javascript:;', 'icon' => 'fa-list', 'sub' => [
//                ['label' => '订单列表', 'url' => '/order/index'],
//            ]],
//            ['label' => 'banner轮播图', 'url' => 'javascript:;', 'icon' => 'fa-picture-o', 'sub' => [
//                ['label' => 'banner列表', 'url' => '/banner/index'],
//            ]],
//            ['label' => '财务管理', 'url' => 'javascript:;', 'icon' => 'fa-android', 'sub' => [
//                ['label' => '提现记录', 'url' => '/withdraw/index'],
//                //['label' => '充值记录', 'url' => '/recharge/index'],
//                ['label' => '流水列表', 'url' => '/capital-flow/index'],
//                ['label' => '支付对账表', 'url' => '/pay/index'],
//            ]],
            ['label' => '文章管理', 'url' => 'javascript:;', 'icon' => 'fa-file-word-o', 'sub' => [
                ['label' => '文章列表', 'url' => '/post/index'],
                ['label' => '文章分类', 'url' => '/post-class/index'],
            ]],
            ['label' => '管理员管理', 'url' => 'javascript:;', 'icon' => ' fa-user', 'sub' => [
                ['label' => '管理员列表', 'url' => '/manager/index'],
            ]],
            ['label' => '系统设置', 'url' => 'javascript:;', 'icon' => 'fa-wrench', 'sub' => [
                ['label' => '站点信息', 'url' => '/system-setting/index'],
                ['label' => '系统公告', 'url' => '/notice/index'],
            ]],
        ],
    ],
];
