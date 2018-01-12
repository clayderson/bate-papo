<?php

	namespace app\controllers\api\v1;
	use \app\models\messageModel;
	use \app\models\roomModel;
	use \app\models\userModel;

	class messageController extends \app\controllers\controller
	{
		public function find($request, $response, $args)
		{
			$roomId = (int) $args['roomId'] ?? '';

			if ($roomId < 1) {
				return $response->withJson([
					'errorMessage' => 'invalid roomId'
				], 400);
			}

			$minutesAgo = 15;
			$data = messageModel::findAllByRoomIdAndMinutesAgo($roomId, $minutesAgo)[0] ?? null;

			if (!empty($data)) {
				return $response->withJson([
					'id' => $data['id'],
					'roomId' => $data['roomId'],
					'userId' => $data['userId'],
					'message' => htmlspecialchars($data['message'])
				], 200);
			}

			return $response->withJson([], 200);
		}

		public function findAtLimitAndOffset($request, $response, $args)
		{
			$roomId = (int) $args['roomId'] ?? '';
			$limit = (int) $args['limit'] ?? 20;
			$offset = (int) $args['offset'] ?? 0;

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

			if ($offset < 0) {
				return $response->withJson([
					'errorMessage' => 'invalid offset'
				], 400);
			}

			$minutesAgo = 15;
			$data = messageModel::findAllByRoomIdAndMinutesAgoAndLimitAndOffset($roomId, $minutesAgo, $limit, $offset)[0] ?? null;

			if (!empty($data)) {
				return $response->withJson([
					'id' => $data['id'],
					'roomId' => $data['roomId'],
					'userId' => $data['userId'],
					'message' => htmlspecialchars($data['message'])
				], 200);
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

			$roomData = roomModel::findById($roomId)[0] ?? null;

			if (!empty($roomData)) {
				$userData = userModel::findByToken($userToken)[0] ?? null;
				if (!empty($userData)) {
					return $response->withJson([
						'id' => messageModel::save($roomId, $userData['id'], $message, $ip, $userAgent),
						'roomId' => $roomId,
						'userId' => $userData['id'],
						'message' => htmlspecialchars($message)
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

