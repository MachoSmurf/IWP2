<?
	if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}	
?>

<!DOCTYPE html>
<html>
<head>
	<title><? echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="./stylesheet.css">
	<script src="./ckeditor/ckeditor.js"></script>
</head>
<body>
<div class="wrapper">

	<div class="navigation">
		<div class="logoBox">
			<!--Hier is plek voor een logo-->
			<div id="logo">Vriendenboek</div>
			<div id="username">Ingelogd als: <? echo $_SESSION["username"]; 
			if ($_SESSION["rechten"] == 1) {echo "(A)";}?></div>
		</div>
		<div class="navBody">
			<ul>
				<li><a href="?p=home" <?php if ($activePage == "home") {echo "class=\"active\"";} ?> >Home</a></li>
				<li><a href="?p=vrienden" <?php if ($activePage == "vrienden") {echo "class=\"active\"";} ?> >Vrienden</a></li>
				<li><a href="?p=auto" <?php if ($activePage == "auto") {echo "class=\"active\"";} ?> >Automerken</a></li>
				<li><a href="?p=koppel" <?php if ($activePage == "koppel") {echo "class=\"active\"";} ?> >Koppelingen Maken</a></li>
				</a></li>
				<li><a href="?p=vriendauto" <?php if ($activePage == "vriendauto") {echo "class=\"active\"";} ?> >Auto&#x27;s van vrienden</a></li>
			</ul>
		</div>
		<div class="navFooter">
			<!-- No user settings implemented yet so hide the button
			<div id="footerSettingsButton">
				<a href="?p=settings"><img src="./img/settings.png" width="16" height="16" alt="settings"></a>
			</div>-->
			<div id="footerLogoutButton">
				<a href="?p=logout"><img src="./img/power.png" width="16" height="16" alt="settings"></a>
			</div>
		</div>
	</div>

	<div class="content">

		<div class="contentNav">
			
		</div>

		<div class="pageContent">			
		<!--Content starts here-->