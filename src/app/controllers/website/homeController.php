<?php

	namespace app\controllers\website;
	use \easycurl;
	use \Exception;

	class homeController extends \app\controllers\controller
	{
		public function __invoke($request, $response, $args)
		{
			$apiResponse = easycurl::request(easycurl::POST, 201,
				__API_URL . '/v1/room', [
					'title' => 'Bate papo'
				]
			);

			if (!$apiResponse['isOk']) {
				throw new Exception('Ops! Parece que nossa API não está bem');
			}

			return $response->withStatus(302)->withHeader(
				'Location',
				$this->router->pathFor('chat', ['roomCode' => $apiResponse['code']]
			));
		}
	}
