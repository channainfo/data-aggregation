<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';

defined( 'YII_DEBUG' ) or define( 'YII_DEBUG', true );

// Specify how many levels of call stack should be shown in each log message
defined( 'YII_TRACE_LEVEL' ) or define( 'YII_TRACE_LEVEL', 3 );

$config=dirname(__FILE__).'/protected/config/main.php';

require_once($yii);
Yii::createWebApplication($config)->run();
