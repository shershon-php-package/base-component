## base-component
常用的基础包

## 安装
* 配置composer.json
```json
{
  "require-dev": {
    "shershon-php-package/base-component": "^1.0.0"
  },
  "config": {
    "secure-http": false
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/shershon-php-package/base-component.git"
    }
  ]
}
```
* composer  require --ignore-platform-reqs shershon/base
* rm -rf vendor/shershon/base/.git

## 更新包版本
* composer  require --ignore-platform-reqs shershon/base:1.0.0(替换成指定的版本)
* rm -rf vendor/shershon/base/.git

## 使用
### 1. 基本用法
* 前期准备,设置常量。在框架入口或脚本入口添加请求id和开始时间
   ```
   define('INDEX_START', microtime(true));//基于计算耗时
   define('REQUEST_ID', 'PHP_' . uniqid(gethostname() . '_'));//用于追踪请求链路
   ```
* 打印日志
  $logger的的instance只需要初始化一次，一般放在ServiceProvider中初始化一次，后期只使用LogFacade门面类打印日志
``` php
    $config = [
        'file' => '/logs/test.log' //设置日志路径
    ];
    $logger = new Logger();
    $logger->setConfig($config);
    LogFacade::setInstance($logger);
    //日志不区分模块
    LogFacade::info('test', ['title' => 'this is test'])
    //日志区分模块,便与搜索
    LogFacade::info('module:message', ['title' => 'this is test'])
```
### 2. lumen/laravel 框架接入
* 在public/index.php和artisan文件中增加常量
```
 define('INDEX_START', microtime(true));//基于计算耗时
 define('REQUEST_ID', 'PHP_' . uniqid(gethostname() . '_'));//用于追踪请求链路
```
* 在AppServiceProvider的boot方法初始化日志服务, .env配置配置日志文件路径
```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Shershon\Base\Logger\LogFacade;
use Shershon\Base\Logger\LogFacadeFacade;
use Shershon\Base\Logger\LogFacadeger;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        //配置日志服务
        $config = [
            'file' => env('LOG_FILE', '/tmp/test.log'), //设置日志路径
            'level' => env('LOG_LEVEL', 'DEBUG'),
        ];
        if (class_exists("Shershon\Base\Logger\LogFacade")) {
            $logger = new Logger();
            $logger->setConfig($config);
            LogFacade::setInstance($logger);
        }
    }
}
```
* 在需要打日志的地方使用 LogFacade日志门面类打印日志即可
 ``` 
    LogFacade::info('module:message', ['title' => 'this is test'])
 ```

### 3. thinkphp6.0 框架接入(有service概念)
* 在public/index.php(每个不同的应用入口)和think文件中增加常量
```
 define('INDEX_START', microtime(true));//基于计算耗时
 define('REQUEST_ID', 'PHP_' . uniqid(gethostname() . '_'));//用于追踪请求链路
```
* 在AppService的boot方法初始化日志服务,.env配置配置日志文件路径
```php
class AppService extends Service
{
    public function register()
    {
        // 服务注册
    }

    public function boot()
    {
        // 服务启动
        //配置日志服务
        $config = [
            'file' => env('log.path', '/logs').'/test.log' //设置日志路径
        ];
        if (class_exists("Shershon\Base\Logger\LogFacade")) {
            $logger = new Logger();
            $logger->setConfig($config);
            LogFacade::setInstance($logger);
        }
    }
}
```
* 在需要打日志的地方使用 LogFacade日志门面类打印日志即可
 ``` 
    LogFacade::info('module:message', ['title' => 'this is test'])
 ```