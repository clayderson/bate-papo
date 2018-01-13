<?php

	namespace app\controllers\api\v1;

	use \app\models\messagesTable;
	use \app\models\roomsTable;
	use \app\models\usersTable;
	use \app\models\viewMessagesUsersTable;

	class messagesController extends \app\controllers\api\v1\controller
	{
		public function find($request, $response, $args)
		{
			$roomId = (int) $args['roomId'] ?? '';

			if ($roomId < 1) {
				return $response->withJson([
					'errorMessage' => 'invalid roomId'
				], 400);
			}

			$data = viewMessagesUsersTable::findAllByRoomIdAndMinutesAgo($roomId, 15) ?? null;

			if (!empty($data)) {
				foreach ($data as $message) {
					$id = $message['id'];
					$messages[$id] = $message;
					$messages[$id]['message'] = htmlspecialchars(strip_tags($message['message']));
					$messages[$id]['createdAt'] = strftime('Enviada em %d de %b às %H:%M', $message['createdAt']);
					unset($messages[$message['id']]['id']);
					unset($messages[$message['id']]['ip']);
					unset($messages[$message['id']]['userAgent']);
				}

				return $response->withJson($messages, 200);
			}

			return $response->withJson([], 200);
		}

		public function findAtLimitAndMinId($request, $response, $args)
		{
			$roomId = (int) $args['roomId'] ?? '';
			$limit = (int) $args['limit'] ?? 20;
			$minId = (int) $args['minId'] ?? 0;

			if ($roomId < 1) {
				return $response->withJson([
					'errorMessage' => 'invalid roomId'
				], 400);
			}

			if ($limit < 10 || $limit > 50) {
				return $response->withJson([
					'errorMessage' => 'invalid limit (min 10, max 50)'
				], 400);
			}

			if ($minId < 0) {
				return $response->withJson([
					'errorMessage' => 'invalid minId'
				], 400);
			}

			$data = viewMessagesUsersTable::findAllByRoomIdAndMinutesAgoAndLimitAndMinId($roomId, $minId, $limit, 15) ?? null;

			if (!empty($data)) {
				foreach ($data as $message) {
					$id = $message['id'];
					$messages[$id] = $message;
					$messages[$id]['message'] = htmlspecialchars(strip_tags($message['message']));
					$messages[$id]['createdAt'] = strftime('Enviada em %d de %b às %H:%M', $message['createdAt']);
					unset($messages[$message['id']]['id']);
					unset($messages[$message['id']]['ip']);
					unset($messages[$message['id']]['userAgent']);
				}

				return $response->withJson($messages, 200);
			}

			return $response->withJson([], 200);
		}

		public function save($request, $response, $args)
		{
			$roomId = $request->getParsedBody()['roomId'] ?? '';
			$userToken = $request->getParsedBody()['userToken'] ?? '';
			$message = $request->getParsedBody()['message'] ?? '';
			$ip = null;
			$userAgent = null;

			if ($roomId < 1) {
				return $response->withJson([
					'errorMessage' => 'invalid roomId'
				], 400);
			}

			if (strlen($userToken) !== 32) {
				return $response->withJson([
					'errorMessage' => 'invalid userToken'
				], 400);
			}

			if (empty($message)) {
				return $response->withJson([
					'errorMessage' => 'message is empty'
				], 400);
			}

			$roomData = roomsTable::findById($roomId)[0] ?? null;

			if (!empty($roomData)) {
				$userData = usersTable::findByToken($userToken)[0] ?? null;
				if (!empty($userData)) {
					return $response->withJson([
						'id' => messagesTable::save($roomId, $userData['id'], $message, $ip, $userAgent),
						'roomId' => $roomId,
						'userId' => $userData['id'],
						'message' => htmlspecialchars(strip_tags($message))
					], 201);
				}

				return $response->withJson([
					'errorMessage' => 'userToken not exists'
				], 400);
			}

			return $response->withJson([
				'errorMessage' => 'roomId not exists'
			], 400);
		}
	}
