<?php

	namespace app\controllers\api\v1;

	use \utility;
	use \app\models\roomsTable;

	class roomsController extends \app\controllers\api\v1\controller
	{
		public function find($request, $response, $args)
		{
			$code = $args['code'] ?? '';

			if (!utility::checkChatCode($code)) {
				return $response->withJson([
					'errorMessage' => 'invalid code'
				], 400);
			}

			$data = roomsTable::findByCode($code)[0] ?? null;

			if (!empty($data)) {
				return $response->withJson([
					'id' => $data['id'],
					'code' => $data['code'],
					'title' => htmlspecialchars($data['title'])
				], 200);
			}

			return $response->withJson([], 200);
		}

		public function save($request, $response, $args)
		{
			$title = $request->getParsedBody()['title'] ?? '';

			if (strlen($title) < 1 || strlen($title) > 18) {
				return $response->withJson([
					'errorMessage' => 'invalid title (min 1 char, max 18 chars)'
				], 400);
			}

			$roomCode = false;

			while (!$roomCode) {
				$roomCode = utility::getRandomChatCode();
				if (!utility::checkChatCode($roomCode)) {
					$roomCode = false;
				} else {
					if (!empty(roomsTable::findByCode($roomCode))) {
						$roomCode = false;
					}
				}
			}

			return $response->withJson([
				'id' => roomsTable::save($roomCode, $title),
				'code' => $roomCode,
				'title' => htmlspecialchars($title)
			], 201);
		}

		public function update($request, $response, $args)
		{
			$code = $args['code'] ?? '';

			if (!utility::checkChatCode($code)) {
				return $response->withJson([
					'errorMessage' => 'invalid code'
				], 400);
			}

			$data = roomsTable::findByCode($code)[0] ?? null;

			if (!empty($data)) {
				$title = $request->getParsedBody()['title'] ?? $data['title'];

				if (strlen($title) < 1 || strlen($title) > 18) {
					return $response->withJson([
						'errorMessage' => 'invalid title (min 1 char, max 18 chars)'
					], 400);
				}

				return $response->withJson([
					'affectedRows' => roomsTable::updateById($data['id'], $title)
				], 201);
			}

			return $response->withJson([
				'affectedRows' => 0
			], 200);
		}
	}
