<?php

	class db
	{
		/**
		 * @var PDO
		 */
		private static $instance;

		private static $host = '127.0.0.1';
		private static $user = 'root';
		private static $pass = '';
		private static $database = 'test';
		private static $port = 3306;

		private static function connect()
		{
			$dsn  = 'mysql:host=' . self::$host . ';';
			$dsn .= 'port=' . self::$port . ';';
			$dsn .= 'dbname=' . self::$database . ';';
			$dsn .= 'charset=utf8';

			self::$instance = new PDO($dsn, self::$user, self::$pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_TIMEOUT => 5
			]);
		}

		public static function credentials($host, $user, $pass, $database, $port)
		{
			self::$host = $host;
			self::$user = $user;
			self::$pass = $pass;
			self::$database = $database;
			self::$port = $port;
		}

		public static function instance()
		{
			if (is_null(self::$instance)) {
				self::connect();
			}

			return self::$instance;
		}
	}
