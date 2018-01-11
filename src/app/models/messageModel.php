<?php

	namespace app\models;
	use \db;

	class messageModel extends \app\models\model
	{
		public static function findAll()
		{
			$stmt = db::instance()->prepare(
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
			$stmt = db::instance()->prepare(
				'SELECT * FROM `message` WHERE `roomId` = :roomId'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByRoomIdAndMinutesAgo($roomId, $minutesAgo)
		{
			$stmt = db::instance()->prepare(
				'SELECT * FROM `message` WHERE `roomId` = :roomId AND `createdAt` >= :createdAt'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':createdAt', (time() - (60 * $minutesAgo)));
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByRoomIdAndLimitAndOffset($roomId, $limit, $offset)
		{
			$stmt = db::instance()->prepare(
				'SELECT * FROM `message` WHERE `roomId` = :roomId LIMIT :limit OFFSET :offset'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':limit', $limit);
			$stmt->bindValue(':offset', $offset);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByUserId($userId)
		{
			$stmt = db::instance()->prepare(
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
			$stmt = db::instance()->prepare(
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
			$stmt = db::instance()->prepare(
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
			$stmt = db::instance()->prepare(
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
				return db::instance()->lastInsertId();
			}

			return false;
		}

		public static function del($id)
		{
			$stmt = db::instance()->prepare(
				'DELETE FROM `message` WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}

		public static function updateById($id, $message)
		{
			$stmt = db::instance()->prepare(
				'UPDATE `message` SET `message` = :message WHERE `id` = :id LIMIT 1'
			);

			$stmt->bindValue(':message', $message);
			$stmt->bindValue(':id', $id);
			$stmt->execute();

			return $stmt->rowCount();
		}
	}
