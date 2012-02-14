<?php
// change the following paths if necessary



$yiit=dirname(__FILE__).'/../../../framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

//$phactory = dirname(__FILE__)."/phactory_init.php" ; 
//require_once($phactory);

//require_once  dirname(__FILE__)."/PhactoryConnection.php";
require_once dirname(dirname(__FILE__)).'/extensions/goutte/goutte.phar' ;
Yii::createWebApplication($config);
