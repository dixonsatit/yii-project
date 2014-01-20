<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Yii Project',
    //'theme'=>'ihospital',
    // preloading 'log' component
    'preload' => array('log', 'noty'),
    'aliases' => array(
        'RestfullYii' => realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
        'bootstrap' => 'application.modules.bootstrap'
    ),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.modules.rights.*',
        'application.modules.rights.components.*',
        'application.modules.bootstrap.components.*',
    ),
    'modules' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule',
        ),
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'generatorPaths' => array(
                'bootstrap.gii'
            ),
            'class' => 'system.gii.GiiModule',
            'password' => '1234',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'user' => array(
            'tableUsers' => 'tbl_users',
            'tableProfiles' => 'tbl_profiles',
            'tableProfileFields' => 'tbl_profiles_fields',
        ),
        'rights' => array(
            'install' => FALSE,
        ),
    ),
    // application components
    'components' => array(
        'BSHtml' => array(
            'class' => 'bootstrap.components.BSHtml'
        ),
        'noty' => array(
            'class' => 'ext.dsnotify.DsNotify',
        //'layout'=>'topRight',
        //'theme'=>'default'
        ),
        'nodeSocket' => array(
            'class' => 'application.extensions.yii-node-socket.lib.php.NodeSocket',
            'host' => '127.0.0.1', // default is 127.0.0.1, can be ip or domain name, without http
            'port' => 3001      // default is 3001, should be integer
        ),
        'authManager' => array(
            'class' => 'RDbAuthManager',
        ),
        'user' => array(
            'class' => 'RWebUser',
            'allowAutoLogin' => true,
            'loginUrl' => array('/user/login'),
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            // Restful web service
            /* 'rules' => require(
              dirname(__FILE__) . '/../extensions/starship/RestfullYii/config/routes.php'
              ), */
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        /* 'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ), */
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=yii-project',
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                /* array(
                  'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                  'ipFilters' => array('127.0.0.1', '192.168.1.215'),
                  ), */
                /* array(
                  'class' => 'CEmailLogRoute',
                  'levels' => 'error',
                  'emails' => 'ihospitallog@gmail.com',
                  ), */
                array(
                    'class' => 'CDbLogRoute',
                    'levels' => 'warning , error', //trace , info , profile , warning , error
                    'connectionID' => 'db',
                    'autoCreateLogTable' => true,
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.vendor.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                /* 'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                  'mode'              => '', //  This parameter specifies the mode of the new document.
                  'format'            => 'A4', // format A4, A5, ...
                  'default_font_size' => 0, // Sets the default document font size in points (pt)
                  'default_font'      => '', // Sets the default font-family for the new document.
                  'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                  'mgr'               => 15, // margin_right
                  'mgt'               => 16, // margin_top
                  'mgb'               => 16, // margin_bottom
                  'mgh'               => 9, // margin_header
                  'mgf'               => 9, // margin_footer
                  'orientation'       => 'P', // landscape or portrait orientation
                  ) */
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
// this is used in contact page
        'adminEmail' => 'dixonsatit@gmail.com',
    ),
);
