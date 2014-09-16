<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

$doc_root = dirname(dirname(dirname(__FILE__)));

// Absolute image directory
$img_dir = $doc_root.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'images';
// Relative image directory
$img_dir_rel = DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'images';

// This is the config URL for the node server
// if (PRODUCTION) {
//    $analysis_engine_url = PROD_URL;
// } else {
//$analysis_engine_url = 'localhost:8124';
$analysis_engine_url = 'http://157.22.244.226/ae_web_api.php';
// }

if (file_exists("/home/thomp")) {
	$db_user = 'root';
	$db_pass = 'c0xp5x1';
} else {
	$db_user = 'arqbrand_dev01';
	$db_pass = 'afieh87234g1';
}


return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ArQnet',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'834o7ho',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('localhost:8000', '70.173.153.205', '127.0.0.1','::1', '157.22.244.95'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('login'),
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'gii' => 'gii',
				'gii/<controller:\w+>' => 'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<action:[\w\-]+>' => 'site/<action>',
			),
		),
		'showScriptName' => false,
		//'db'=>array(
		//	'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		//),
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=arqbrand_dev01',
			'emulatePrepare' => true,
			'username' => $db_user,
			'password' => $db_pass,
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
					'levels'=>'trace,error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'docRoot'=>$doc_root,
		'imageDir'=>$img_dir,
		'relativeImageDir'=>$img_dir_rel,
		'noteImageDir'=>$img_dir.DIRECTORY_SEPARATOR.'notes',
		'userImageDir'=>$img_dir.DIRECTORY_SEPARATOR.'user',
		'relativeNoteImageDir'=>$img_dir_rel.DIRECTORY_SEPARATOR.'notes',
		'relativeUserImageDir'=>$img_dir_rel.DIRECTORY_SEPARATOR.'user',
		'analysis_engine_url'=>$analysis_engine_url,
	),
);
