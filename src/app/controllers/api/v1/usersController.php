<?php

	namespace app\controllers\api\v1;

	use \utility;
	use \app\models\usersTable;

	class usersController extends \app\controllers\api\v1\controller
	{
		public function find($request, $response, $args)
		{
			$token = $args['token'] ?? '';

			if (strlen($token) !== 32) {
				return $response->withJson([
					'errorMessage' => 'invalid token'
				], 400);
			}

			$data = usersTable::findByToken($token)[0] ?? null;

			if (!empty($data)) {
				return $response->withJson([
					'id' => $data['id'],
					'nickname' => htmlspecialchars($data['nickname']),
					'color' => $data['color'],
					'token' => $data['token']
				], 200);
			}

			return $response->withJson([], 200);
		}

		public function save($request, $response, $args)
		{
			$nickname = $request->getParsedBody()['nickname'] ?? utility::getRandomNickname(25);
			$color = $request->getParsedBody()['color'] ?? 'a588be';

			if (strlen($nickname) < 3 || strlen($nickname) > 25) {
				return $response->withJson([
					'errorMessage' => 'invalid nickname (min 3 char, max 25 chars)'
				], 400);
			}

			if (!preg_match('/^(?:[0-9a-fA-F]{3}){1,2}$/', $color)) {
				return $response->withJson([
					'errorMessage' => 'invalid color'
				], 400);
			}

			$userToken = false;

			while (!$userToken) {
				$userToken = md5(bin2hex(random_bytes(10)));
				if (!empty(usersTable::findByToken($userToken))) {
					$userToken = false;
				}
			}

			return $response->withJson([
				'id' => usersTable::save($nickname, $color, $userToken),
				'nickname' => htmlspecialchars($nickname),
				'color' => htmlspecialchars($color),
				'token' => $userToken
			], 201);
		}

		public function update($request, $response, $args)
		{
			$token = $args['token'] ?? '';

			if (strlen($token) !== 32) {
				return $response->withJson([
					'errorMessage' => 'invalid token'
				], 400);
			}

			$data = usersTable::findByToken($token)[0] ?? null;

			if (!empty($data)) {
				$nickname = $request->getParsedBody()['nickname'] ?? $data['nickname'];
				$color = $request->getParsedBody()['color'] ?? $data['color'];

				if (strlen($nickname) < 3 || strlen($nickname) > 25) {
					return $response->withJson([
						'errorMessage' => 'invalid nickname (min 3 char, max 25 chars)'
					], 400);
				}

				if (!preg_match('/^(?:[0-9a-fA-F]{3}){1,2}$/', $color)) {
					return $response->withJson([
						'errorMessage' => 'invalid color'
					], 400);
				}

				return $response->withJson([
					'affectedRows' => usersTable::updateById($data['id'], $nickname, $color)
				], 201);
			}

			return $response->withJson([
				'affectedRows' => 0
			], 200);
		}
	}
