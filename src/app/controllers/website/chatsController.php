<?php

	namespace app\controllers\website;

	use \utility;
	use \easycurl;
	use \Exception;

	class chatsController extends \app\controllers\website\controller
	{
		public function __invoke($request, $response, $args)
		{
			$roomCode = $args['roomCode'] ?? '';

			if (!utility::checkChatCode($roomCode)) {
				return $response->withStatus(302)->withHeader(
					'Location',
					$this->router->pathFor('index')
				);
			}

			$roomDetails = easycurl::request(easycurl::GET, 200,
				__API_URL . '/v1/rooms/' . $roomCode
			);

			if (!$roomDetails['isOk']) {
				if (isset($createRoom['errorMessage'])) {
					throw new Exception($createRoom['errorMessage']);
				}

				throw new Exception('Ops! Parece que há algo de errado com nossa API');
			}

			if (!isset($roomDetails['id'])) {
				return $response->withStatus(301)->withHeader(
					'Location',
					$this->router->pathFor('index')
				);
			}

			return $this->view->render($response->withStatus(200), '/chat.twig', [
				'title' => $roomDetails['title'],
				'roomId' => $roomDetails['id']
			]);
		}
	}
