<?php

	define('__APP_DEBUG', getenv('DEBUG') === 'ON' ? true : false);

	define('__LOGGER', getenv('LOGGER') === 'ON' ? true : false);
	define('__LOGGER_FILE', getenv('LOGGER_FILE'));

	define('__DIR_ROOT', realpath(__DIR__ . '/../..'));
	define('__DIR_VENDOR', __DIR_ROOT . '/vendor');
	define('__DIR_SRC', __DIR_ROOT . '/src');
	define('__DIR_APP', __DIR_SRC . '/app');
	define('__DIR_BOOTSTRAP', __DIR_SRC . '/bootstrap');
	define('__DIR_CLASSES', __DIR_SRC . '/classes');
	define('__DIR_PUBLIC', __DIR_SRC . '/public');
	define('__DIR_VIEWS', __DIR_APP . '/views');

	define('__MYSQL_HOST', getenv('MYSQL_HOSTNAME'));
	define('__MYSQL_USER', getenv('MYSQL_USERNAME'));
	define('__MYSQL_PASS', getenv('MYSQL_PASSWORD'));
	define('__MYSQL_DBNAME', getenv('MYSQL_DATABASE'));
	define('__MYSQL_PORT', getenv('MYSQL_PORT'));
