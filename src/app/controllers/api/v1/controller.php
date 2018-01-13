<?php

	namespace app\controllers\api\v1;

	use \db;
	use \app\models\roomsTable;
	use \app\models\messagesTable;
	use \app\models\usersTable;
	use \app\models\viewMessagesUsersTable;

	class controller
	{
		protected $container;

		public function __construct($container)
		{
			$this->container = $container;

			roomsTable::setInstance(db::instance());
			messagesTable::setInstance(db::instance());
			usersTable::setInstance(db::instance());
			viewMessagesUsersTable::setInstance(db::instance());
		}

		public function __get($property)
		{
			if ($this->container->{$property}) {
				return $this->container->{$property};
			}

			return null;
		}
	}
