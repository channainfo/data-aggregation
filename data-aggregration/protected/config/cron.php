<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Data aggregration',

	// preloading 'log' component
	'preload'=>array('log'),
	'import'=>array(  
		'application.models.*',
		'application.components.*',
	),
	// application components
	'components'=>array(
		'user'=>array(
			'allowAutoLogin'=>true,
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
            'errorAction'=>'site/error',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				)
			),
		),
	)
);