<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

header("content-type:text/html; charset=utf-8");

define('APP_DEBUG',true);
define('DB_FIELD_CACHE',false);
define('HTML_CACHE_ON',false);//关闭缓存
// 绑定Api模块到当前入口文件 

define('BIND_MODULE','Api');
// 定义应用目录
define('APP_PATH','./Application/');
//定义常量
define("SITE_URL","http://localhost/");



// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单