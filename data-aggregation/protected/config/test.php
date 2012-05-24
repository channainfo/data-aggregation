<?php
$configTest = CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=server_oi_test',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
      'enableProfiling'=>true,   
      ),
		),
	)
);
return $configTest ;