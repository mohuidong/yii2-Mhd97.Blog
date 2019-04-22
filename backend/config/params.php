<?php
return [
    'adminEmail' => '914685196@qq.com',

    'menu' => [
        'label' => 'Mhd97.top-博客管理后台', 'url' => '/site/index', 'sidebar' => [
            ['label' => '用户管理', 'url' => 'javascript:;', 'icon' => 'fa-users', 'sub' => [
                ['label' => '用户列表', 'url' => '/user/index'],
            ]],
            ['label' => '文章管理', 'url' => 'javascript:;', 'icon' => 'fa-file-word-o', 'sub' => [
                ['label' => '文章列表', 'url' => '/posts/index'],
                ['label' => '文章分类', 'url' => '/post-class/index'],
            ]],
            ['label' => '问答管理', 'url' => 'javascript:;', 'icon' => 'fa-question', 'sub' => [
                ['label' => '问题列表', 'url' => '/question/index'],
                ['label' => '答案列表', 'url' => '/answer/index'],
            ]],
            ['label' => '管理员管理', 'url' => 'javascript:;', 'icon' => ' fa-user', 'sub' => [
                ['label' => '管理员列表', 'url' => '/manager/index'],
                ['label' => '管理员权限设置', 'url' => '/auth'],
            ]],
            ['label' => '系统设置', 'url' => 'javascript:;', 'icon' => 'fa-wrench', 'sub' => [
                ['label' => '站点信息', 'url' => '/system-setting/index'],
//                ['label' => '系统公告', 'url' => '/notice/index'],
            ]],
        ],
    ],

    'permissions' => [
        'user' => [
            'name' => '会员列表管理' ,
            'items' => [
                'userList' => '会员管理列表',
                'userCud' => '会员列表增删改查' ,
            ],
        ],
        'post' => [
            'name' => '文章管理-文章列表',
            'items' => [
                'postList' => '内容管理-投诉列表-列表',
                'postCud' => '内容管理-投诉列表-增删改',
            ],
        ],
        'postClass' => [
            'name' => '文章管理-文章分类',
            'items' => [
                'postClassList' => '内容管理-文章分类-列表',
                'postClassCud' => '内容管理-文章分类-增删改',
            ],
        ],
        'question' => [
            'name' => '问答管理-问题列表',
            'items' => [
                'questionList' => '问答管理-问题列表-列表',
                'questionCud' => '问答管理-问题列表-增删改',
            ],
        ],
//        'answer' => [
//            'name' => '问答管理-答案列表',
//            'items' => [
//                'answerList' => '问答管理-问题列表-列表',
//                'answerCud' => '问答管理-问题列表-增删改',
//            ],
//        ],
        'SystemSetting' => [
            'name' => '系统设置-系统配置',
            'items' => [
                'SystemSettingList' => '系统设置-系统配置-列表',
                'SystemSettingCud' => '系统设置-系统配置-增删改',
            ],
        ],
        'notice' => [
            'name' => '系统设置-系统公告',
            'items' => [
                'noticeList' => '系统设置-系统公告-列表',
                'noticeCud' => '系统设置-系统公告-增删改',
            ],
        ],
    ]
];
