<?php

	$app->get('/', \app\controllers\website\homeController::class)->setName('home');
	$app->get('/chat/{secret}', \app\controllers\website\chatController::class)->setName('chat');
