<?php

	use \app\controllers\api;

	$app->group('/api/v1', function() {
		$this->group('/rooms', function() {
			$this->post('', api\v1\roomsController::class . ':save');
			$this->get('/{code}', api\v1\roomsController::class . ':find');
			$this->put('/{code}', api\v1\roomsController::class . ':update');
		});

		$this->group('/users', function() {
			$this->post('', api\v1\usersController::class . ':save');
			$this->get('/{token}', api\v1\usersController::class . ':find');
			$this->put('/{token}', api\v1\usersController::class . ':update');
		});

		$this->group('/messages', function() {
			$this->post('', api\v1\messagesController::class . ':save');
			$this->get('/{roomId}', api\v1\messagesController::class . ':find');
			$this->get('/{roomId}/{minId}/{limit}', api\v1\messagesController::class . ':findAtLimitAndMinId');
		});
	});
