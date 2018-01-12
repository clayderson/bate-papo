<?php

	$app->group('/api/v1', function() {
		$this->group('/room', function() {
			$this->post('', \app\controllers\api\v1\roomController::class . ':save');
			$this->get('/{code}', \app\controllers\api\v1\roomController::class . ':find');
			$this->put('/{code}', \app\controllers\api\v1\roomController::class . ':update');
		});

		$this->group('/user', function() {
			$this->post('', \app\controllers\api\v1\userController::class . ':save');
			$this->get('/{token}', \app\controllers\api\v1\userController::class . ':find');
			$this->put('/{token}', \app\controllers\api\v1\userController::class . ':update');
		});

		$this->group('/message', function() {
			$this->post('', \app\controllers\api\v1\messageController::class . ':save');
			$this->get('/{roomId}', \app\controllers\api\v1\messageController::class . ':find');
			$this->get('/{roomId}/{limit}/{offset}', \app\controllers\api\v1\messageController::class . ':findAtLimitAndOffset');
		});
	});
