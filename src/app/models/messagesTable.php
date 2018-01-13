<?php

	namespace app\models;

	use \PDO;

	class messagesTable extends \app\models\model
	{
		public static function findAll()
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `message`'
			);

			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByRoomId($roomId)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `message` WHERE `roomId` = :roomId'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByUserId($userId)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `message` WHERE `userId` = :userId'
			);

			$stmt->bindValue(':userId', $userId);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByIp($ip)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `message` WHERE `ip` = :ip'
			);

			$stmt->bindValue(':ip', $ip);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findById($id)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `message` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function save($roomId, $userId, $message, $ip, $userAgent)
		{
			$stmt = self::db()->prepare(
				'INSERT INTO `message` (`roomId`, `userId`, `message`, `ip`, `userAgent`)
				VALUES (:roomId, :userId, :message, :ip, :userAgent)'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':userId', $userId);
			$stmt->bindValue(':message', $message);
			$stmt->bindValue(':ip', $ip);
			$stmt->bindValue(':userAgent', $userAgent);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::db()->lastInsertId();
			}

			return false;
		}

		public static function del($id)
		{
			$stmt = self::db()->prepare(
				'DELETE FROM `message` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}

		public static function updateById($id, $message)
		{
			$stmt = self::db()->prepare(
				'UPDATE `message` SET `message` = :message WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':message', $message);
			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}
	}
