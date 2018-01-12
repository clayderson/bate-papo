<?php

	namespace app\controllers\api\v1;
	use \app\models\userModel;

	class userController extends \app\controllers\controller
	{
		private function getRandomNickname()
		{
			$cuteAnimals = [
				'Feneco', 'Társio-das-Filipinas', 'Panda', 'Foca da Groelândia',
				'Lontra-marinha', 'Colibri-abelha-cubano', 'Alpaca', 'Golfinho',
				'Corça', 'Chinchila', 'Peixe-palhaço', 'Baleia-branca',
				'Panda-vermelho', 'Pinguim', 'Coala', 'Suricate', 'Camaleão',
				'Lóris-lento-pigmeu', 'Hipopótamo-pigmeu', 'Bicho-preguiça'
			];

			return $cuteAnimals[array_rand($cuteAnimals)];
		}

		public function find($request, $response, $args)
		{
			$token = $args['token'] ?? '';

			if (strlen($token) !== 32) {
				return $response->withJson([
					'errorMessage' => 'invalid token'
				], 400);
			}

			$data = userModel::findByToken($token)[0] ?? null;

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
			$nickname = $request->getParsedBody()['nickname'] ?? $this->getRandomNickname();
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
				if (!empty(userModel::findByToken($userToken))) {
					$userToken = false;
				}
			}

			return $response->withJson([
				'id' => userModel::save($nickname, $color, $userToken),
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

			$data = userModel::findByToken($token)[0] ?? null;

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
					'affectedRows' => userModel::updateById($data['id'], $nickname, $color)
				], 201);
			}

			return $response->withJson([
				'affectedRows' => 0
			], 200);
		}
	}

