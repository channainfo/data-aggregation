<?php
date_default_timezone_set("Asia/Phnom_Penh");
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Data Aggregation',  
	// preloading 'log' component
	//'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(  
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
	
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123456',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
      "class" => "DaUser"  
		),  
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
      'showScriptName' => true,  
			'rules'=>array(
        '' => "main/dashboard",
        'login' => "user/login",  
        '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=server_oi_dev',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			 // use 'site/error' action to display errors
       'errorAction'=>'error/error',
     )),
//		'log'=>array(
//      'class'=>'CLogRouter',
//      'routes'=>array(
//          array(
//              'class'=>'CFileLogRoute',
//              'levels'=>'trace, info',
//              'categories'=>'system.*',
//          ),
//          
          
          
//       array(
//          'class'=>'CWebLogRoute',
//          'categories'=>'system.db.CDbCommand',
//          'showInFireBug'=>true,
//       ) ,
//       array(
//          'class'=>'CFileLogRoute',
//          'levels'=>'trace',
//          'categories'=>'system.db.*',
//          'logFile'=>'sql.log',
//      ),   
          
          
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
//			),
//		),
//	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
    'version' => "1.0b"  
    //'tablePrefix', 'Da_'  
	),
);