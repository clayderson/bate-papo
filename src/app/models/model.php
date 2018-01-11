<?php

	namespace app\models;

	class model
	{
		/**
		 * Converte dados numéricos retornados pelo banco
		 * de dados: string to int/float
		 */
		public static function typeSanitize($data)
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
	}
