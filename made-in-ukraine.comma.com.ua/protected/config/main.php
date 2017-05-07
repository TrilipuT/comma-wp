<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'Comma',
	'preload' => array('log'),
	'sourceLanguage' => 'en',
	'language' => 'ru',
	'import' => array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
		'application.extensions.eauth.*',
		'application.extensions.eauth.services.*',
	),
	'behaviors' => array(
		'runEnd' => array(
			'class' => 'application.behaviors.WebApplicationEndBehavior',
		),
	),
	'components' => array(
		'user' => array(
			// enable cookie-based authentication
			'allowAutoLogin' => true,
			'identityCookie' => array(
				'domain' => '.comma.com.ua'
			),
		),
		'session' => array(
			'cookieParams' => array(
				'domain' => '.comma.com.ua'
			),
		),
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
			'defaultRoles' => array('guest'),
			'itemTable' => 'auth_item',
			'itemChildTable' => 'auth_item_child',
			'assignmentTable' => 'auth_assignment',
		),
		'loid' => array(
			'class' => 'application.extensions.lightopenid.loid',
		),
		'eauth' => array(
			'class' => 'application.extensions.eauth.EAuth',
			'popup' => true,
			'cache' => false,
			'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.

			'services' => array( // You can change the providers and their classes.
				 'facebook' => array(
					 // register your app here: https://developers.facebook.com/apps/
					 'class' => 'FacebookOAuthService',
					 'client_id' => '786003321416896',
					 'client_secret' => '20d3c85aacdd06a10e8b36fc137210e1',
				 ),

				 'vkontakte' => array(
					 // register your app here: https://vk.com/editapp?act=create&site=1
					 'class' => 'VKontakteOAuthService',
					 'client_id' => '4067430',
					 'client_secret' => 'Z8frLPUNBhYxTTMlODjD',
				 ),
			),// end  services
		),
		'db' => array(
			'connectionString' => 'mysql:host=comma.cokehadd6xln.eu-central-1.rds.amazonaws.com;dbname=commah',
			'emulatePrepare' => true,
			'username' => 'comma',
			'password' => 'veryhardpassword',
			'charset' => 'utf8',
		),
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
	),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params' => array(
		// this is used in contact page 
		'webRoot' => dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'),
		'defaultLang' => 'ru',
		'createUrlLang' => true,
		'isMobile' => false,
		'main-domain' => 'comma.com.ua',
	),
);
