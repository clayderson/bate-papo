<?php

	namespace app\controllers\website;

	use \easycurl;
	use \Exception;

	class indexController extends \app\controllers\website\controller
	{
		public function __invoke($request, $response, $args)
		{
			$createRoom = easycurl::request(easycurl::POST, 201,
				__API_URL . '/v1/rooms', [
					'title' => 'Bate papo'
				]
			);

			if (!$createRoom['isOk']) {
				if (isset($createRoom['errorMessage'])) {
					throw new Exception($createRoom['errorMessage']);
				}

				throw new Exception('Ops! Parece que há algo de errado com nossa API');
			}

			return $response->withStatus(302)->withHeader(
				'Location',
				$this->router->pathFor('chats', ['roomCode' => $createRoom['code']]
			));
		}
	}
