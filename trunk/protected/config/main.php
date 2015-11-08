<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '吆喝API系统',
    'timeZone' => 'Asia/Chongqing',
    'language' => 'zh_cn',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*'
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '192.168.*', '::1'),
        ),
    ),
    // application components
    'components' => array(
//        'cache' => array(
//            'class' => 'CMemCache',
//            'useMemcached' => true,
//            'servers' => array(
//                array(
//                    'host' => '192.168.2.10',
//                    'port' => 11211,
//                ),
//            ),
//        ),
       
//        'cache' => array(
//            'class' => 'CMemCache',
//            'useMemcached' => true,
//            'keyPrefix' => 'yaohe_app',
//            'servers' => array(
//                array(
//                    'host' => '127.0.0.1',
//                    'port' => 11211,
//                ),
//            ),
//        ),
        #角色验证组件
//        'authManager' => array(;
//            'class' => 'CDbAuthManager',
//            'itemTable' => 'AuthItem',
//            'itemChildTable' => 'AuthItemChild',
//            'assignmentTable' => 'AuthAssignment',
//            'defaultRoles' => array('CUSTOMER'),
//            'showErrors' => false,
//        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '' => 'site/index',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database
//        //db local
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=yaohe_app',
            'emulatePrepare' => true,
            'username' => 'yaohe_app',
            'password' => 'Dd6a9KSmAJDKsUtV',
            'charset' => 'utf8',
            'enableProfiling' => false,
            'enableParamLogging' => false,
        ),
       //db online test
//        'db' => array(
//            'connectionString' => 'mysql:host=localhost;dbname=yaohe_app_test',
//            'emulatePrepare' => true,
//            'username' => 'yaohe_app_test',
//            'password' => '123',
//            'charset' => 'utf8',
//            'enableProfiling' => false,
//            'enableParamLogging' => false,
//        ),
//       //db online
//        'db' => array(
//            'connectionString' => 'mysql:host=localhost;dbname=yaohe_app',
//            'emulatePrepare' => true,
//            'username' => 'yaohe_app',
//            'password' => 'Dd6a9KSmAJDKsUtV',
//            'charset' => 'utf8',
//            'enableProfiling' => false,
//            'enableParamLogging' => false,
//        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error,warning,debug,info',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class' => 'CWebLogRoute',
              'levels' => 'error', //级别为trace
              #'categories' => 'system.db.*' //只显示关于数据库信息,包括数据库连接,数据库执行语句
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        /* 短信参数 - start */
        'apns_server_url' => 'tcp://127.0.0.1:55559',
        //'sms_server_url' => 'tcp://192.168.2.10:55558',
        'sms_server_url' => 'tcp://127.0.0.1:55558',
        'validcode_len' => 6, //短信验证码长度
        'validcode_msg' => '[%s]短信验证码，%s分钟内有效！此验证码也是您的登录密码，请妥善保管！', //短信验证码内容格式
        'validcode_ts' => 3, //短信验证码发送间隔/有效时间（分钟）
        /* 短信参数  - end */
        'local_test' => TRUE,
        /* 高德地图 api key */
        'gaode_map_key' => '437dc25e951aa5c1808972f272e11c35',
    ),
);
