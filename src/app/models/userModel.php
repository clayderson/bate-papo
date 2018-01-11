<?php

	namespace app\models;
	use \db;

	class userModel extends \app\models\model
	{
		public static function findAll()
		{
			$stmt = db::instance()->prepare(
				'SELECT * FROM `user`'
			);

			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByRoomId($roomId)
		{
			$stmt = db::instance()->prepare(
				'SELECT * FROM `user` WHERE `roomId` = :roomId'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findById($id)
		{
			$stmt = db::instance()->prepare(
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
			$stmt = db::instance()->prepare(
				'SELECT * FROM `user` WHERE `token` = :token LIMIT 1'
			);

			$stmt->bindValue(':token', $token);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function save($roomId, $nickname, $color, $token)
		{
			$stmt = db::instance()->prepare(
				'INSERT INTO `user` (`roomId`, `nickname`, `color`, `token`)
				VALUES (:roomId, :nickname, :color, :token)'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':nickname', $nickname);
			$stmt->bindValue(':color', $color);
			$stmt->bindValue(':token', $token);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return true;
			}

			return false;
		}

		public static function del($id)
		{
			$stmt = db::instance()->prepare(
				'DELETE FROM `user` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return true;
			}

			return false;
		}

		public static function updateById($id, $nickname, $color)
		{
			$stmt = db::instance()->prepare(
				'UPDATE `user` SET `nickname` = :nickname, `color` = :color WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':nickname', $nickname);
			$stmt->bindValue(':color', $color);
			$stmt->bindValue(':id', $id);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return true;
			}

			return false;
		}
	}
