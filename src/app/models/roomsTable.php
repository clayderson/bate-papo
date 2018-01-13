<?php

	namespace app\models;

	class roomsTable extends \app\models\model
	{
		public static function findAll()
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `room`'
			);

			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findById($id)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `room` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findByCode($code)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `room` WHERE `code` = :code LIMIT 1'
			);

			$stmt->bindValue(':code', $code);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function save($code, $title)
		{
			$stmt = self::db()->prepare(
				'INSERT INTO `room` (`code`, `title`)
				VALUES (:code, :title)'
			);

			$stmt->bindValue(':code', $code);
			$stmt->bindValue(':title', $title);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::db()->lastInsertId();
			}

			return false;
		}

		public static function del($id)
		{
			$stmt = self::db()->prepare(
				'DELETE FROM `room` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}

		public static function updateById($id, $title)
		{
			$stmt = self::db()->prepare(
				'UPDATE `room` SET `title` = :title WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':title', $title);
			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}
	}
