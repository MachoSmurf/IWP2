<?
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}

/**
	*	connects to the database
	*
	*	@return boolean returns true or false on the login
	*/
	function DB_connect()
	{
		global $settings;

		$dbConn = new mysqli($settings["db_host"], $settings["db_username"], $settings["db_password"], $settings["db_database"]);
		
		if ($dbConn->connect_error)
		{
			die ("Database Connection Failed!");
		}

		return $dbConn;
	}

/**
	*	closes the connection to the database
	*
	*	@param MySQLi object to close
	*/
	function DB_close($connection)
	{
		$connection->close();
	}
?>