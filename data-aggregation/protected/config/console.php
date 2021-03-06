<?php
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
date_default_timezone_set("Asia/Phnom_Penh");
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Data aggregation',
  'import'=>array(  
		'application.models.*',
		'application.components.*',
    ),  
	'components'=>array(	
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=server_oi_dev',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		)
	),
);