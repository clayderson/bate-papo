<?php

	namespace app\controllers\website;

	class homeController extends \app\controllers\controller
	{
		public function __invoke($request, $response, $args)
		{
			if ($request->isGet()) {
				return $response->withStatus(302)->withHeader(
					'Location',
					$this->router->pathFor('chat', ['roomCode' => bin2hex(random_bytes(5))]
				));
			}

			return $response;
		}
	}
