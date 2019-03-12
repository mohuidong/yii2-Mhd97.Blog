<?php
return [
    'adminEmail' => '914685196@qq.com',

    'menu' => [
        'label' => 'Mhd97.top-博客管理后台', 'url' => '/site/index', 'sidebar' => [
            ['label' => '用户管理', 'url' => 'javascript:;', 'icon' => 'fa-users', 'sub' => [
                ['label' => '用户列表', 'url' => '/user/index'],
            ]],
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
