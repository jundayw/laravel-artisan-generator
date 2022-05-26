# 安装方法
命令行下, 执行 composer 命令安装:
````
composer require jundayw/laravel-artisan-generator
````

# 配置及模板
authentication package that is simple and enjoyable to use.

发布配置文件
````
php artisan vendor:publish --tag=artisan-generator-config
````
发布模板文件
````
php artisan vendor:publish --tag=artisan-generator-views
````
发布配置及模板文件
````
php artisan vendor:publish --provider="Jundayw\LaravelArtisanGenerator\GeneratorServiceProvider"
````

# 使用方法

### 分步交互命令：generator
可生成Controller/Repository/Model/Request/View及相对应的方法(Method)
````
php artisan generator
````

### 生成模型命令：generator:model
````
php artisan generator:model Test
php artisan generator:model Test --parent
````
````
[
  "DummyModelRootNamespace" => "App\Models"
  "DummyModelClassNamespace" => "App\Models\Test"
  "DummyModelClass" => "Test"
  "DummyModelVariable" => "test"
  "DummyNamespace" => "App\Models"
  "DummyRootNamespace" => "App\"
  "NamespacedDummyUserModel" => "App\User"
  "DummyClass" => "Test"
]
````

### 生成表单验证命令：generator:request
````
php artisan generator:request Test/TestStoreFormRequest
php artisan generator:request Test/TestStoreFormRequest --parent
````
````
[
  "DummyRepositoryRootNamespace" => "App\Http\Requests"
  "DummyRequestClassNamespace" => "App\Http\Requests\Test\TestStoreFormRequest"
  "DummyRequestClass" => "TestStoreFormRequest"
  "DummyRequestVariable" => "testStoreFormRequest"
  "DummyNamespace" => "App\Http\Requests\Test"
  "DummyRootNamespace" => "App\"
  "NamespacedDummyUserModel" => "App\User"
  "DummyClass" => "TestStoreFormRequest"
]
````

### 生成仓储层命令：generator:repository
````
php artisan generator:repository Test/TestRepository
php artisan generator:repository Test/TestRepository --parent
php artisan generator:repository Test/TestRepository --model=Test
php artisan generator:repository Test/TestRepository --model=Test --method
````
````
[
  "DummyModelRootNamespace" => "App\Models"
  "DummyModelClassNamespace" => "App\Models\Test"
  "DummyModelClass" => "Test"
  "DummyModelVariable" => "test"
  "DummyRepositoryRootNamespace" => "App\Repositories"
  "DummyRepositoryClassNamespace" => "App\Repositories\Test\TestRepository"
  "DummyRepositoryClass" => "TestRepository"
  "DummyRepositoryVariable" => "testRepository"
  "DummyRepositoryUrl" => "test/test"
  "DummyRepositoryRoute" => "test.test"
  "DummyNamespace" => "App\Repositories\Test"
  "DummyRootNamespace" => "App\"
  "NamespacedDummyUserModel" => "App\User"
  "DummyClass" => "TestRepository"
]
````

### 生成视图命令：generator:view
````
php artisan generator:view Test/Test --view=list
````
````
[
  "DummyViewRootNamespace" => "resources\views"
  "DummyViewClassNamespace" => "resources\views\Test\Test"
  "DummyViewClass" => "Test"
  "DummyViewVariable" => "test"
  "DummyViewUrlMethod" => "test/test/list"
  "DummyViewRouteMethod" => "test.test.list"
  "DummyViewUrlClass" => "test/test"
  "DummyViewRouteClass" => "test.test"
  "DummyViewMethod" => "list"
]
````

### 生成控制层命令：generator:controller
````
php artisan generator:controller Test/TestController
php artisan generator:controller Test/TestController --parent
php artisan generator:controller Test/TestController --model=Test
php artisan generator:controller Test/TestController --repository=Test/TestRepository
php artisan generator:controller Test/TestController --repository=Test/TestRepository --model=Test
php artisan generator:controller Test/TestController --repository=Test/TestRepository --model=Test --method --view=create --view=edit --view=list
php artisan generator:controller Test/TestController --repository=Test/TestRepository --model=Test --method --request=Test/TestStoreFormRequest --request=Test/TestUpdateFormRequest
````
````
[
  "DummyRepositoryRootNamespace" => "App\Repositories"
  "DummyRepositoryClassNamespace" => "App\Repositories\Test\TestRepository"
  "DummyRepositoryClass" => "TestRepository"
  "DummyRepositoryVariable" => "testRepository"
  "DummyModelRootNamespace" => "App\Models"
  "DummyModelClassNamespace" => "App\Models\Test"
  "DummyModelClass" => "Test"
  "DummyModelVariable" => "test"
  "DummyRequestRootNamespace" => "App\Http\Requests"
  "DummyRequestClass1Namespace" => "App\Http\Requests\Test\TestStoreFormRequest"
  "DummyRequestClass1" => "TestStoreFormRequest"
  "DummyRequestVariable1" => "testStoreFormRequest"
  "DummyRequestClass2Namespace" => "App\Http\Requests\Test\TestUpdateFormRequest"
  "DummyRequestClass2" => "TestUpdateFormRequest"
  "DummyRequestVariable2" => "testUpdateFormRequest"
  "DummyViewVariableCreate" => "test.test.create"
  "DummyViewVariableEdit" => "test.test.edit"
  "DummyViewVariableList" => "test.test.list"
  "DummyLabel" => "App"
  "DummyControllerRootNamespace" => "App\Http\Controllers"
  "DummyControllerClassNamespace" => "App\Http\Controllers\Test\TestController"
  "DummyControllerClass" => "TestController"
  "DummyControllerVariable" => "testController"
  "DummyNamespace" => "App\Http\Controllers\Test"
  "DummyRootNamespace" => "App\"
  "NamespacedDummyUserModel" => "App\User"
  "DummyClass" => "TestController"
]
````

### 使用流程
 - 创建测试数据库
````
CREATE TABLE `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
````
 - 编写测试路由
````
Route::namespace('Test')->name('test.')->group(function () {
    Route::prefix('test')->name('test.')->group(function () {
        Route::get('list', 'TestController@list')->name('list');
        Route::get('create', 'TestController@create')->name('create');
        Route::post('store', 'TestController@store')->name('store');
        Route::get('edit', 'TestController@edit')->name('edit');
        Route::post('update', 'TestController@update')->name('update');
        Route::get('destroy', 'TestController@destroy')->name('destroy');
    });
});
````
 - 执行命令
````
php artisan generator:controller Test/TestController --repository=Test/TestRepository --model=Test --method --request=Test/TestStoreFormRequest --request=Test/TestUpdateFormRequest --view=create --view=edit --view=list
````
或
````
php artisan generator
````
 - 调试成功
 - 发布模板文件
````
php artisan vendor:publish --tag=artisan-generator-views
````
 - 自定义Model模板（继承、配置及默认方法）
 - 自定义Request模板（继承、配置及默认方法）
 - 自定义Repository模板（继承及默认方法）
 - 自定义Controller模板（继承及默认方法）
 - 自定义View视图文件（View模板默认create/edit/list，可根据具体情况新增或删减，通过 --view 参数调用新增模板）
 - Well Done!
