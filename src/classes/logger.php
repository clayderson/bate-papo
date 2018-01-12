<?php

	class logger
	{
		private static $filename;

		const ERROR = 'ERROR';
		const WARNING = 'WARNING';
		const DEBUG = 'DEBUG';

		private static function getFileContent()
		{
			return file_exists(self::$filename) ? json_decode(file_get_contents(self::$filename), true) : [
				self::ERROR => [],
				self::WARNING => [],
				self::DEBUG => [],
			];
		}

		private static function save($filedata)
		{
			return file_put_contents(self::$filename, json_encode($filedata,
				JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_PRESERVE_ZERO_FRACTION
			));
		}

		public static function setFile(string $filename)
		{
			self::$filename = $filename;
		}

		public static function add(string $type, string $message)
		{
			if (self::$filename) {
				$filedata = self::getFileContent();

				foreach ($filedata[$type] as $key => $value) {
					if ($value['message'] === $message) {
						$messageArrayKey = $key;
						break;
					}
				}

				if (!isset($messageArrayKey)) {
					$filedata[$type][] = [
						'message' => $message,
						'count' => 1,
						'firstOccurence' => time(),
						'lastOccurence' => time(),
					];
				} else {
					$filedata[$type][$messageArrayKey]['count']++;
					$filedata[$type][$messageArrayKey]['lastOccurence'] = time();
				}

				return self::save($filedata);
			}

			return false;
		}
	}
