<?php

	class easycurl
	{
		const GET = 'GET';
		const POST = 'POST';
		const PUT = 'PUT';
		const DELETE = 'DELETE';

		public static function request($method, $status, $url, $post = [])
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

			if ($method === self::POST || $method === self::PUT) {
				$jsonData = json_encode($post,
					JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_FORCE_OBJECT
				);

				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Content-Type: application/json', 'Content-Length: ' . strlen($jsonData)
				]);
			}

			$response = json_decode(curl_exec($ch), true);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			curl_close($ch);

			$response['isOk'] = ($httpCode === $status) ? true : false;
			return $response;
		}
	}
