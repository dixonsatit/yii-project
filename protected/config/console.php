<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Console Application',
    // preloading 'log' component
    'preload' => array('log'),
    'commandMap' => array(
        'node-socket' => 'application.extensions.yii-node-socket.lib.php.NodeSocketCommand'
    ),
    // application components
    'components' => array(
        /* 'db' => array(
          'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
          ), */
        'nodeSocket' => array(
            'class' => 'application.extensions.yii-node-socket.lib.php.NodeSocket',
            'host' => '127.0.0.1', // default is 127.0.0.1, can be ip or domain name, without http
            'port' => 3001      // default is 3001, should be integer
        ),
        // uncomment the following to use a MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=yii-project',
            'enableProfiling' => true,
            'enableParamLogging' => true,
            'username' => 'root',
            'password' => 'root',
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
);
