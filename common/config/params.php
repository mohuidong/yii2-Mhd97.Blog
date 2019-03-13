<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    // 阿里云oss配置
    'aliyunOss'=>[
        // 服务器外网地址
        'ossServer' => 'oss-cn-shenzhen.aliyuncs.com',
        'ossServerInternal' => 'oss-cn-shenzhen.aliyuncs.com',
        'accessKeyId' => 'LTAIYggOQXWhEfxs',
        'accessKeySecret' => 'U3orab6l7QxWNn9lQ5lScJ2klZ1yov',
        'bucket' => 'mhd97-blog',
        'endPoint' => 'oss-cn-shenzhen.aliyuncs.com',
    ],

    'domain' => 'http://mhd97-blog.oss-cn-shenzhen.aliyuncs.com/',
    'webuploader' => [
        // 后端处理图片的地址，value 是相对的地址
        'uploadUrl' => '/uploader-file',
        // 多文件分隔符
        'delimiter' => ',',
        // 基本配置
        'baseConfig' => [
            'defaultImage' => 'http://img1.imgtn.bdimg.com/it/u=2056478505,162569476&fm=26&gp=0.jpg',
            'disableGlobalDnd' => true,
            'accept' => [
                'title' => 'Images',
                'extensions' => 'gif,jpg,jpeg,bmp,png',
                'mimeTypes' => 'image/*',
            ],
            'pick' => [
                'multiple' => false,
            ],
        ],
    ],
];
