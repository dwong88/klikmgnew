<?php

// uncomment the following to define a path alias
Yii::setPathOfAlias('parse','/Users/wiyantotan/Development/htdocs/ghoursmghol/public_html/protected/components/Parse');
//Yii::setPathOfAlias('parse','/var/www/ghoursme/public_html/protected/components/Parse');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'id'=>'ghoursmghol_id9', //http://www.yiiframework.com/wiki/135/single-sign-on-across-multiple-subdomains/
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ghoursmghol',
    'theme'=>'ghoursmghol',
    'timeZone'=>'Asia/Bangkok',
    // preloading 'log' component
    'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
        'parse.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1234567',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
//        'demo',
        'mg'
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true, // If true then all persistent data will be stored in cookie.
            'stateKeyPrefix'=>'_idghoursmghol',
            'identityCookie'=>array( //http://www.yiiframework.com/wiki/135/single-sign-on-across-multiple-subdomains/
                'path' => '/',
                'domain' => '.ghoursmghol.local',
            ),
		),
/*
        'session' => array(
            'timeout' => 43200,
            //http://www.yiiframework.com/wiki/135/single-sign-on-across-multiple-subdomains/
            'savePath' => dirname($_SERVER['SCRIPT_FILENAME']).'/../ghoursmghol_sessions/',
            'cookieMode' => 'allow',
            'cookieParams' => array(
                'path' => '/',
                'domain' => '.ghoursmghol.local',
                'httpOnly' => true,
            ),
        ),
        */

//        'request'=>array(
//            'enableCsrfValidation'=>true,
//            'enableCookieValidation'=>true,
            //'hostInfo'=>'http://indoresnew.com',
            //'baseUrl'=>'http://indoresnew.com',
            //'scriptUrl'=>'index.php',
//        ),

		// uncomment the following to enable URLs in path-format
//		'urlManager'=>array(
//			'urlFormat'=>'path',
//            'showScriptName'=>false,
//            'caseSensitive'=>true,
//			'rules'=>array(
//			    'gii'=>'gii',
//				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
//				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
//				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
//                '<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
//                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\w+>'=>'<module>/<controller>/<action>',
//                '<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
//			),
//		),

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

        // Used at CDataColumn format view.
        'format'=>array(
            'class'=>'application.components.Formatter',
        ),
        'datetime'=>array(
            'class'=>'application.components.DateTimeParser',
        ),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
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
		'adminEmail'=>'website@g-hours.me',
        'veritrans'=>array(
            'serverKey'=>'SB-Mid-server-cbE9RmVnbhADGi_Qlar31Hm1',
            'isProduction'=>false,
            'isSanitized'=>true,
            'is3ds'=>true,
            'clientKey'=>'SB-Mid-client-96jawtCGS_iRIi8E'
        ),
	),
);
