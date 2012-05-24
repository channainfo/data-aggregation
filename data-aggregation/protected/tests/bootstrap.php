<?php
// change the following paths if necessary



$yiit=dirname(__FILE__).'/../../../framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

//$phactory = dirname(__FILE__)."/phactory_init.php" ; 
//require_once($phactory);
//require_once  dirname(__FILE__)."/PhactoryConnection.php";

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once dirname(dirname(__FILE__)).'/extensions/goutte/goutte.phar' ;
Yii::createWebApplication($config);


// automatically send every new message to available log routes
Yii::getLogger()->autoFlush = 1;
// when sending a message to log routes, also notify them to dump the message
// into the corresponding persistent storage (e.g. DB, email)
Yii::getLogger()->autoDump = true;