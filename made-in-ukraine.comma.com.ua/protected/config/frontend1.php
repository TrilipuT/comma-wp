<?php

return CMap::mergeArray(

	require_once(dirname(__FILE__) . '/main.php'),

	array(

		// стандартный контроллер
		'defaultController' => 'site',
		'theme'=>'test',

		// компоненты
		'components' => array(
			'urlManager' => array(
				'class' => 'application.components.UrlManager',
				'urlFormat' => 'path',
				'showScriptName' => false,
				'rules' => array(
					'<language:\w+>{2}/ajax/<action:\w+>' => 'ajax/<action>',
					'/cron/<action:\w+>' => 'cron/<action>',
					'login' => 'site/login',

					'http://made-in-ukraine.comma.com.ua/' => 'C_articles/ukraine',

					// REST patterns
					array('api/list', 'pattern' => 'http://api.comma.com.ua/<model:\w+>/', 'verb' => 'GET'),
					array('api/list', 'pattern' => 'http://api.comma.com.ua/<model:\w+>/<cat_name:\w+>/', 'verb' => 'GET'),
					array('api/view', 'pattern' => 'http://api.comma.com.ua/<model:\w+>/(<cat_name:\w+>/)?<id:\d+>/', 'verb' => 'GET'),
					array('site/error', 'pattern' => 'http://api.comma.com.ua/', 'verb' => 'GET'),

				),

			),
			'errorHandler' => array(
				// use 'site/error' action to display errors
				'errorAction' => 'site/error',
			),

		),
	)
);