<?
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}


	//This array store settings that can be used throughout the system

	$settings = array(	"db_host"			=>		"localhost",
						"db_username"		=>		"root",
						"db_password"		=>		"",
						"db_database"		=>		"IWP2",
						"timeout"			=>		"3600",
						"page_title_prefix"	=>		"IWP2 - Vriendenboek",
						"salt_lenght"		=>		"64",
		)
?>