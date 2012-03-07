<?php

// change the following paths if necessary
require_once dirname(__FILE__)."/components/DaTool.php";

$default = DaTool::env($argv);


$yiic=dirname(__FILE__).'/../../framework/yiic.php';
$config=dirname(__FILE__)."/config/{$default}.php";
require_once($yiic);
