<?
	session_start();
	error_reporting(E_ALL);
	define("IN_SYSTEM", true);
	require "./inc/settings.inc.php";
	require "./inc/functions.inc.php";
	require "./inc/db.inc.php";

	$showlogin = array();
	$showlogin[0] = true;

	$dbConn = DB_connect();

	if (checklogin())
	{
		//show content	
		handlePage();
	}
	else
	{
		$showlogin[0] = true;
		if ((isset($_POST["submit"])) && (isset($_POST["username"])) && (isset($_POST["password"])))
		{
			//user is trying to login
			if (preformLogin($_POST["username"], $_POST["password"]))
			{
				header("Location: ./index.php");
				$showlogin[0] = false;
			}
			else
			{
				//wrong credentials
				$showlogin[0] = true;
				$showlogin[1] = $_POST["username"];
			}
		}
		else
		{
			//show login screen
			$showlogin[0] = true;
		}

		if ($showlogin[0])
		{
			$title	=	$settings["page_title_prefix"] . "Login";
			include './content/login.inc.php';
		}
	}

	DB_close($dbConn);
?>