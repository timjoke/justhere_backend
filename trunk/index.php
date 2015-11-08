<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';


// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require dirname(__FILE__).'/protected/business/busApi.php';
require dirname(__FILE__).'/protected/business/busUlitity.php';
require dirname(__FILE__).'/protected/business/busSms.php';
require dirname(__FILE__).'/protected/business/enum.php';
require dirname(__FILE__).'/protected/business/carbon.php';
require dirname(__FILE__).'/protected/business/operResult.php';
require dirname(__FILE__).'/protected/business/returnCode.php';
require dirname(__FILE__).'/protected/business/busValidcode.php';
require dirname(__FILE__).'/protected/business/simple_html_dom.php';

require_once($yii);
Yii::createWebApplication($config)->run();
