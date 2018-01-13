<?php

use \app\controllers\website;

	$app->get('/', website\indexController::class)->setName('index');
	$app->get('/chats/{roomCode}', website\chatsController::class)->setName('chats');
