<?php

return [
    'controller' => '\Http\Controllers',    // 控制器命名空间
    'model' => '\Models',                   // 模型命名空间
    'repository' => '\Repositories',        // 仓库命名空间
    'request' => '\Http\Requests',          // 表单验证命名空间
    'view' => '\views',               // 视图模板路径
    'publishes' => [
        'stubs' => 'artisan-generator-stubs',   // 通过修改命令模板配置可生成多套命令模板
        'views' => 'artisan-generator-views',   // 通过修改视图模板配置可生成多套视图模板
    ],
    'default' => [
        'label' => '管理员',
        'controller' => sprintf('%s/%s%s', 'Backend', 'Manager', 'Controller'),
        'view' => join(',', [
            'create',
            'edit',
            'list',
        ]),
        'request' => join(',', [
            sprintf('%s/%s%s%s', 'Backend', 'Manager', 'Create', 'Request'),
            sprintf('%s/%s%s%s', 'Backend', 'Manager', 'Update', 'Request'),
        ]),
        'repository' => sprintf('%s/%s%s', 'Backend', 'Manager', 'Repository'),
        'model' => 'Manager',
    ],
];
