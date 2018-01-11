<?php

	require __DIR__ . '/constants.php';
	require __DIR_VENDOR . '/autoload.php';

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	$app = new \Slim\App([
		'settings' => [
			'displayErrorDetails' => __APP_DEBUG,
			'addContentLengthHeader' => true,
		]
	]);

	$container = $app->getContainer();

	if (__LOGGER) {
		logger::setFile(__LOGGER_FILE);
	}

	db::credentials(__MYSQL_HOST, __MYSQL_USER, __MYSQL_PASS, __MYSQL_DBNAME, __MYSQL_PORT);

	$container['notFoundHandler'] = function($container) {
		return function($request, $response) {
			logger::add(
				logger::WARNING,
				"Página não encontrada: {$request->getUri()->getPath()}"
			);

			$specialPaths = ['/', '/favicon.ico'];

			if (in_array($request->getUri()->getPath(), $specialPaths)) {
				return $response->withJson([
					'errorMessage' => 'Not found'
				], 404);
			}

			return $response->withStatus(301)->withHeader('Location', '/?redirectReason=404');
		};
	};

	$container['notAllowedHandler'] = function($container) {
		return function($request, $response, $methods) {
			return $response->withStatus(301)->withHeader('Location', '/?redirectReason=405');
		};
	};

	$container['errorHandler'] = function($container) {
		return function($request, $response, $e) use ($container) {
			if (is_object($e)) {
				$errorMessage = "{$e->getMessage()} {$e->getFile()} {$e->getLine()}";
				logger::add(logger::ERROR, $errorMessage);

				if (!$container->get('settings')['displayErrorDetails']) {
					unset($errorMessage);
				}
			}

			return $container->view->render($response->withStatus(500), '/error.twig', [
				'errorMessage' => $errorMessage ?? 'Modo de debug desabilitado',
			]);
		};
	};

	$container['phpErrorHandler'] = function($container) {
		return $container['errorHandler'];
	};

	$container['view'] = function($container) {
		$view = new \Slim\Views\Twig(__DIR_VIEWS, [
			'cache' => false,
			'charset' => 'utf-8',
			'strict_variables' => __APP_DEBUG,
			'debug' => __APP_DEBUG,
		]);

		$basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
		$view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));

		$view->getEnvironment()->addGlobal('url', [
			'resolver' => $container['request']->getUri()->getHost(),
			'current' => $container['request']->getUri()->getPath() . '?' . $container['request']->getUri()->getQuery(),
			'previous' => $container['request']->getHeaders()['HTTP_REFERER'][0] ?? '../',
		]);

		$view->getEnvironment()->addGlobal('git', [
			'commit' => substr(shell_exec('git rev-parse HEAD'), 0, 7),
		]);

		return $view;
	};

	require __DIR_BOOTSTRAP . '/routes/website.php';

	$app->run();
