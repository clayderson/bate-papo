<?php

	namespace app\models;

	use \PDO;

	class viewMessagesUsersTable extends \app\models\model
	{
		public static function findAllByRoomIdAndMinutesAgo($roomId, $minutesAgo)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `viewMessageUser` WHERE `roomId` = :roomId AND `createdAt` >= :createdAt'
			);

			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':createdAt', (time() - (60 * $minutesAgo)));
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}

		public static function findAllByRoomIdAndMinutesAgoAndLimitAndMinId($roomId, $minId, $limit, $minutesAgo)
		{
			$stmt = self::db()->prepare(
				'SELECT * FROM `viewMessageUser` WHERE `id` > :minId AND `roomId` = :roomId AND `createdAt` >= :createdAt LIMIT :limit'
			);

			$stmt->bindValue(':minId', $minId);
			$stmt->bindValue(':roomId', $roomId);
			$stmt->bindValue(':createdAt', (time() - (60 * $minutesAgo)));
			$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
			$stmt->execute();

			if ($stmt->rowCount()) {
				return self::typeSanitize($stmt->fetchAll());
			}

			return false;
		}
	}
