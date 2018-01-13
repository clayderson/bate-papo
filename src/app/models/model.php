<?php

	namespace app\models;

	use \Exception;
	use \PDO;

	class model
	{
		/**
		 * @var PDO
		 */
		private static $pdo;

		/**
		 * Converte dados numéricos retornados pelo banco
		 * de dados: string to int/float
		 */
		protected static function typeSanitize($data)
		{
			foreach ($data as $rowNum => $rowValue) {
				foreach ($rowValue as $field => $value) {
					if (is_numeric($value)) {
						$data[$rowNum][$field] = $value + 0;
					}
				}
			}

			return $data;
		}

		protected static function db()
		{
			if (is_object(self::$pdo)) {
				return self::$pdo;
			}

			throw new Exception('Não existe nenhuma instância para ser acessada');
		}

		public static function setInstance(PDO $instance)
		{
			self::$pdo = $instance;
		}
	}
