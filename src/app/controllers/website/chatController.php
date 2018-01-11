<?php

	namespace app\controllers\website;

	class chatController extends \app\controllers\controller
	{
		public function __invoke($request, $response, $args)
		{
			if ($request->isGet()) {
				return $this->view->render($response->withStatus(200), '/chat.twig', [
					'title' => 'Bate papo'
				]);
			}

			return $response;
		}
	}
