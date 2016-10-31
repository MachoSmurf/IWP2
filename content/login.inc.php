<?php

if (!defined("IN_SYSTEM"))
	{
		header("Location: ../index.php");
		die();
	}
	require_once "./securimage/securimage.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="./stylesheet.css">
</head>
<body>
<?php 
	
	if (isset($_POST["reg"]))
	{
		$showReg		=	true;
		$passwordError	=	false;
		$captcha_error	=	false;
		//output registration page or proces form
		if ((isset($_POST["username"])) && (isset($_POST["password"])) && (isset($_POST["passwordConf"])))
		{
			$uName 		=	sanitize($_POST["username"]);

			//check if username allready exists
			$stmt = $dbConn->prepare("SELECT 1 FROM user WHERE username=?");
			$stmt->bind_param("s", $uName);
			$stmt->execute();
			$stmt->bind_result($check);
			$stmt->fetch();

			if ($check != 1)
			{
				//registration has been filled out, user doesn't exists. Do sanitation and add to database
				$image = new Securimage();
				if ($_POST["password"] != $_POST["passwordConf"])
				{
					$passwordError	=	true;
				}
				elseif ($image->check($_POST['captcha_code']) != true) {
			       	$captcha_error = true;
			    }
				else
				{				
					$salt 			=	generateSalt();
					$password 		=	hash("SHA256", $_POST["password"] . $salt);

					$query	=	$dbConn->prepare("INSERT INTO `user` (username, password, salt, rechten) VALUES (?, ?, ?, '0')");
					$query->bind_param("sss", $uName, $password, $salt);

					if ($query->execute())
					{
						mailAdmin($dbConn->insert_id, $uName);
						?>
						<div class="loginWrapper">
						<div class="succes">Gefeliciteerd. Je kunt nu inloggen. Klik <a href="index.php">hier</a> om door te gaan naar het inlogscherm.</div></div>
						<?php
						$showReg	=	false;
					}
					else
					{
						?>
						<div class="error">Er ging iets fout bij het registreren. Probeer het nog eens. <?php //echo $dbConn->error; ?></div>
						<?php
					}
				}
			}
			else
			{
				?>
					<div class="error">De opgegeven gebruikersnaam bestaat al. Probeer het met een andere gebruikersnaam.</div>
				<?php
			}
		}
		
		if ($showReg)
		{
			//show registration page
			?>
			<div class="loginWrapper">
				<div class="regBox">
					<form action="?" method="post">
					<div class="inputLeft">
					Om je te registreren vul je onderstaande formulier in
					</div>
					<div>						
						<div class="inputLeft">Gebruikersnaam:</div>
						<div class="inputRight"><input type="text" name="username"></div>
					</div>
					<div>
						<div class="inputLeft">Wachtwoord:</div>
						<div class="inputRight"><input type="password" name="password"></div>
					</div>
					<div>
						<div class="inputLeft">Bevestig wachtwoord:</div>
						<div class="inputRight"><input type="password" name="passwordConf"></div>
					</div>
					<div class="inputLeft">
						<?php echo Securimage::getCaptchaHtml() ?> 
					</div>
					<div class="inputLeft">
						<input type="submit" value="Registreer" name="reg">
					</div>
					</form>
				</div>
			</div>
			<?php

			if ($passwordError)
			{
				?>
				<script type="text/javascript">
					alert('De ingevoerde wachtwoorden komen niet overeen!');
					</script>
				<?php
			}
			if ($captcha_error)
			{
				?>
				<script type="text/javascript">
					alert('De ingevoerde controlletekst is niet correct!');
					</script>
				<?php
			}
		}
	}
	else
	{
		$username = null;
		if (isset($_POST["username"]))
			{	$username = $_POST["username"];	}
		$timeout = false;
		if (isset($_GET["timeout"]))
			{	$timeout = true;	}
	?>

	<div class="loginWrapper">
		<div id="loginBox">
			<div id="loginText">Vriendenboek</div>
			<div id="LoginInput">
				<form action="" method="post">
					<span class="loginBoxText">Username:</span><span class="loginBoxInput"><input type="text" name="username" value="<?php if ($username!=null) {echo $username;} ?>"></span><br>
					<span class="loginBoxText">Password:</span><span class="loginBoxInput"><input type="password" name="password"></span>
					<div class="loginButton">
						<input type="submit" value="Login" name="submit">
						<form action="" method="post">
							<input type="submit" name="reg" value="Registreer">
						</form>
					</div>				
				</form>
			</div>
		</div>
	</div>

	<?php		
		if ($username!= null)
		{
			?>
			<script type="text/javascript">
				alert('De ingevoerde combinatie van gebruikersnaam en wachtwoord is onjuist!');
				</script>
			<?php
		}
		if ($timeout)
		{
			?>
			<script type="text/javascript">
				alert('Je bent automatisch uitgelogd omdat je te lang inactief bent geweest!');
				</script>
			<?php
		}
	}
	?>
</body>
</html>