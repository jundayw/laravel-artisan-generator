<?php

return [
    'controller' => '\Http\Controllers',    // 控制器命名空间，不含根命名空间(App)
    'model' => '\Models',                   // 模型命名空间，不含根命名空间(App)
    'repository' => '\Repositories',        // 仓库命名空间，不含根命名空间(App)
    'request' => '\Http\Requests',          // 表单验证命名空间，不含根命名空间(App)
    'view' => '\views',                     // 视图模板路径，不含资源路径(resources)
    'resource' => [
        'stubs' => resource_path('views/vendor/artisan-generator-stubs'),   // 通过修改命令模板配置可生成多套命令模板
        'views' => resource_path('views/vendor/artisan-generator-views'),   // 通过修改视图模板配置可生成多套视图模板
    ]
];
