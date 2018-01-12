<?php

	namespace app\controllers\api\v1;
	use \app\models\roomModel;

	class roomController extends \app\controllers\controller
	{
		private function checkCode($code)
		{
			if (preg_match('/^[A-Za-z0-9-]{10}$/', $code)) {
				return true;
			}

			return false;
		}

		public function find($request, $response, $args)
		{
			$code = $args['code'] ?? '';

			if (!$this->checkCode($args['code'])) {
				return $response->withJson([
					'errorMessage' => 'invalid code'
				], 400);
			}

			$data = roomModel::findByCode($code)[0] ?? null;

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
				$roomCode = bin2hex(random_bytes(5));
				if (!$this->checkCode($roomCode)) {
					$roomCode = false;
				} else {
					if (!empty(roomModel::findByCode($roomCode))) {
						$roomCode = false;
					}
				}
			}

			return $response->withJson([
				'id' => roomModel::save($roomCode, $title),
				'code' => $roomCode,
				'title' => htmlspecialchars($title)
			], 201);
		}

		public function update($request, $response, $args)
		{
			$code = $args['code'] ?? '';

			if (!$this->checkCode($code)) {
				return $response->withJson([
					'errorMessage' => 'invalid code'
				], 400);
			}

			$data = roomModel::findByCode($code)[0] ?? null;

			if (!empty($data)) {
				$title = $request->getParsedBody()['title'] ?? $data['title'];

				if (strlen($title) < 1 || strlen($title) > 18) {
					return $response->withJson([
						'errorMessage' => 'invalid title (min 1 char, max 18 chars)'
					], 400);
				}

				return $response->withJson([
					'affectedRows' => roomModel::updateById($data['id'], $title)
				], 201);
			}

			return $response->withJson([
				'affectedRows' => 0
			], 200);
		}
	}

