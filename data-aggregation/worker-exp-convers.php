<?php
ini_set("display_errors", 0);
// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createConsoleApplication($config);

Yii::import("application.vendors.*");
$djPath = dirname(__FILE__).'/protected/vendors/djjob/DJJobConfig.php';
require_once $djPath;

$worker = new DJWorker(array( "queue" => DaConfig::QUEUE_CONVERSION_N_EXPORT,
                              "count" => 0, 
                              "max_attempts" => 2, 
                              "sleep" => 10
                              ));
$worker->start();