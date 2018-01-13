<?php

	require __DIR__ . '/constants.php';
	require __DIR_VENDOR . '/autoload.php';

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	if (__LOGGER) {
		logger::setFile(__LOGGER_FILE);
	}

	$app = new \Slim\App([
		'settings' => [
			'displayErrorDetails' => __APP_DEBUG,
			'addContentLengthHeader' => true,
		]
	]);

	$container = $app->getContainer();

	if (stripos($container['request']->getUri()->getPath(), '/api') === 0) {
		if (__MYSQL_HOST) {
			db::credentials(
				__MYSQL_HOST,
				__MYSQL_USER,
				__MYSQL_PASS,
				__MYSQL_DBNAME,
				__MYSQL_PORT
			);
		}

		$container['notFoundHandler'] = function($container) {
			return function($request, $response) use ($container) {
				return $response->withJson([
					'errorMessage' => 'not found'
				], 404);
			};
		};

		$container['notAllowedHandler'] = function($container) {
			return function($request, $response, $methods) use ($container) {
				return $response->withJson([
					'errorMessage' => 'method not allowed'
				], 405);
			};
		};

		$container['errorHandler'] = function($container) {
			return function($request, $response, $e) use ($container) {
				if (is_object($e)) {
					$errorMessage = "{$e->getMessage()} {$e->getFile()} {$e->getLine()}";
					logger::add(logger::ERROR, $errorMessage);
				}

				if (!isset($errorMessage) || !$container->get('settings')['displayErrorDetails']) {
					$errorMessage = 'Algo não vai bem com nossa API';
				}

				return $response->withJson([
					'errorMessage' => $errorMessage
				], 500);
			};
		};

		$container['phpErrorHandler'] = function($container) {
			return $container['errorHandler'];
		};
	} else {
		$container['notFoundHandler'] = function($container) {
			return function($request, $response) use ($container) {
				$specialPaths = ['/favicon.ico'];
				if (in_array($request->getUri()->getPath(), $specialPaths)) {
					return $response->withStatus(404);
				} else {
					logger::add(
						logger::WARNING,
						"Página não encontrada: {$request->getUri()->getPath()}"
					);

					return $container->view->render($response->withStatus(404), '/error.twig', [
						'statusCode' => 404,
						'errorMessage' => 'Página não encontrada',
					]);
				}
			};
		};

		$container['notAllowedHandler'] = function($container) {
			return function($request, $response, $methods) use ($container) {
				return $container->view->render($response->withStatus(405), '/error.twig', [
					'statusCode' => 405,
					'errorMessage' => 'Método não permitido',
				]);
			};
		};

		$container['errorHandler'] = function($container) {
			return function($request, $response, $e) use ($container) {
				if (is_object($e)) {
					$errorMessage = "{$e->getMessage()} {$e->getFile()} {$e->getLine()}";
					logger::add(logger::ERROR, $errorMessage);
				}

				if (!isset($errorMessage) || !$container->get('settings')['displayErrorDetails']) {
					$errorMessage = 'Algo não vai bem com nossos servidores';
				}

				return $container->view->render($response->withStatus(500), '/error.twig', [
					'statusCode' => 500,
					'errorMessage' => $errorMessage,
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
				'current' => $container['request']->getUri()->getPath() . '?' . $container['request']->getUri()->getQuery(),
				'previous' => $container['request']->getHeaders()['HTTP_REFERER'][0] ?? '../',
			]);

			$view->getEnvironment()->addGlobal('git', [
				'commit' => substr(shell_exec('git rev-parse HEAD'), 0, 7),
			]);

			$view->getEnvironment()->addGlobal('googleAnalytics', [
				'active' => __GOOGLE_ANALYTICS,
				'id' => __GOOGLE_ANALYTICS_ID,
			]);

			return $view;
		};
	}

	require __DIR_BOOTSTRAP . '/routes/api.php';
	require __DIR_BOOTSTRAP . '/routes/website.php';

	$app->run();
