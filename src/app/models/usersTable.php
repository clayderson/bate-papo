<?php

	namespace app\models;

	class usersTable extends \app\models\model
	{
		public static function findAll()
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `user`'
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
				'SELECT * FROM `user` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findByToken($token)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `user` WHERE `token` = :token LIMIT 1'
			);

			$stmt->bindValue(':token', $token);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function save($nickname, $color, $token)
		{
			$stmt = self::db()->prepare(
				'INSERT INTO `user` (`nickname`, `color`, `token`)
				VALUES (:nickname, :color, :token)'
			);

			$stmt->bindValue(':nickname', $nickname);
			$stmt->bindValue(':color', $color);
			$stmt->bindValue(':token', $token);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::db()->lastInsertId();
			}

			return false;
		}

		public static function del($id)
		{
			$stmt = self::db()->prepare(
				'DELETE FROM `user` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}

		public static function updateById($id, $nickname, $color)
		{
			$stmt = self::db()->prepare(
				'UPDATE `user` SET `nickname` = :nickname, `color` = :color WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':nickname', $nickname);
			$stmt->bindValue(':color', $color);
			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}
	}
